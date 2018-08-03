<?php
declare (strict_types = 1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Alchemy\BinaryDriver\Exception\InvalidArgumentException;
use FFMpeg\Filters\Concat\ConcatFilterInterface;
use FFMpeg\Filters\Concat\ConcatFilters;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\VideoInterface;
use Neutron\TemporaryFilesystem\Manager as FsManager;

class Concat extends AbstractMediaType
{
    /**
     * @var string[]
     */
    private $sources;

    /**
     * Creates a new Concat filter.
     *
     * @param string[]     $sources
     * @param FFMpegDriver $driver
     * @param FFProbe      $ffprobe
     */
    public function __construct(array $sources, FFMpegDriver $driver, FFProbe $ffprobe)
    {
        if (empty($sources)) {
            throw new InvalidArgumentException('No sources given');
        }

        parent::__construct($sources[0], $driver, $ffprobe);
        $this->sources = $sources;
    }

    /**
     * Returns the paths to the sources.
     *
     * @return string[]
     */
    public function getSources() : array
    {
        return $this->sources;
    }

    /**
     * @inheritDoc
     *
     * @return ConcatFilters
     */
    public function filters() : ConcatFilters
    {
        return new ConcatFilters($this);
    }

    /**
     * @inheritDoc
     *
     * @return Concat
     */
    public function addFilter(ConcatFilterInterface $filter) : self
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * Saves the concatenated video in the given array,
     * considering that the sources videos are all encoded with the same codec.
     * Please note: If `$streamCopy === true`, filters will not be applied.
     *
     * @param string $outputPathfile
     * @param bool  $streamCopy
     *
     * @return Concat
     *
     * @throws RuntimeException
     */
    public function saveFromSameCodecs(string $outputPathfile, bool $streamCopy = true) : self
    {
        /**
         * @see https://ffmpeg.org/ffmpeg-formats.html#concat
         * @see https://trac.ffmpeg.org/wiki/Concatenate
         */

        // Create the file which will contain the list of videos
        $fs = FsManager::create();
        $sourcesFile = $fs->createTemporaryFile('ffmpeg-concat');

        // Set the content of this file
        $fileStream = @fopen($sourcesFile, 'w');

        if ($fileStream === false) {
            throw new ExecutionFailureException('Cannot open the temporary file.');
        }

        $prependNewline = false;
        foreach ($this->sources as $videoPath) {
            $line = "";

            if ($prependNewline) {
                $line = "\n";
            }

            $line .= "file {$videoPath}";

            fwrite($fileStream, $line);

            $prependNewline = true;
        }

        fclose($fileStream);


        $commands = [
            '-f', 'concat', '-safe', '0',
            '-i', $sourcesFile
        ];

        // Check if stream copy is activated
        if ($streamCopy) {
            $commands[] = '-c';
            $commands[] = 'copy';
        } else {
            // If not, we can apply filters
            foreach ($this->filters as $filter) {
                $commands = array_merge($commands, $filter->apply($this));
            }
        }

        // Set the output file in the command
        $commands[] = $outputPathfile;

        // Execute the command
        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($outputPathfile);
            throw new RuntimeException('Unable to save concatenated video', $e->getCode(), $e);
        } finally {
            $this->cleanupTemporaryFile($sourcesFile);
        }

        return $this;
    }

    /**
     * Saves the concatenated video in the given filename,
     * considering that the sources videos are all encoded with the same codec.
     *
     * @param string $outputPathfile
     *
     * @return Concat
     *
     * @throws RuntimeException
     */
    public function saveFromDifferentCodecs(FormatInterface $format, $outputPathfile) : self
    {
        /**
         * @see https://ffmpeg.org/ffmpeg-formats.html#concat
         * @see https://trac.ffmpeg.org/wiki/Concatenate
         */

        // Create the commands variable
        $commands = [];

        // Prepare the parameters
        $nbSources = 0;
        $files = [];

        // For each source, check if this is a legit file
        // and prepare the parameters
        foreach ($this->sources as $videoPath) {
            $files[] = '-i';
            $files[] = $videoPath;
            $nbSources++;
        }

        $commands = array_merge($commands, $files);

        $commands[] = '-threads';
        $commands[] = (string)$this->driver->getConfiguration()->get('ffmpeg.threads');

        // Set the parameters of the request
        $commands[] = '-filter_complex';

        // build complex filter
        $complexFilter = '';
        for ($i = 0; $i < $nbSources; $i++) {
            $complexFilter .= '[' . $i . ':v:0] [' . $i . ':a:0] ';
        }
        $complexFilter .= 'concat=n=' . $nbSources . ':v=1:a=1 [v] [a]';

        $commands[] = $complexFilter;

        $commands[] = '-map';
        $commands[] = '[v]';
        $commands[] = '-map';
        $commands[] = '[a]';

        // Prepare the filters
        $filters = clone $this->filters;

        $filters->add(new SimpleFilter($format->getExtraParams(), 10));

        if ($format instanceof VideoInterface) {
            if (null !== $format->getVideoCodec()) {
                $filters->add(new SimpleFilter(['-vcodec', $format->getVideoCodec()]));
            }
        }
        if ($format instanceof AudioInterface) {
            if (null !== $format->getAudioCodec()) {
                $filters->add(new SimpleFilter(['-acodec', $format->getAudioCodec()]));
            }
        }

        // Add the filters
        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        if ($format instanceof AudioInterface) {
            if ($format->getAudioKiloBitrate()) {
                $commands[] = '-b:a';
                $commands[] = $format->getAudioKiloBitrate() . 'k';
            }
            if (null !== $format->getAudioChannels()) {
                $commands[] = '-ac';
                $commands[] = (string)$format->getAudioChannels();
            }
        }

        // If the user passed some additional parameters
        if ($format instanceof VideoInterface) {
            if (null !== $format->getAdditionalParameters()) {
                foreach ($format->getAdditionalParameters() as $additionalParameter) {
                    $commands[] = $additionalParameter;
                }
            }
        }

        // Set the output file in the command
        $commands[] = $outputPathfile;

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('Encoding failed', $e->getCode(), $e);
        }

        return $this;
    }
}
