<?php

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
    /** @var array */
    private $sources;

    public function __construct($sources, FFMpegDriver $driver, FFProbe $ffprobe)
    {
        parent::__construct($sources, $driver, $ffprobe);
        $this->sources = $sources;
    }

    /**
     * Returns the path to the sources.
     *
     * @return string
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * {@inheritdoc}
     *
     * @return ConcatFilters
     */
    public function filters()
    {
        return new ConcatFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Concat
     */
    public function addFilter(ConcatFilterInterface $filter)
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * Saves the concatenated video in the given array, considering that the sources videos are all encoded with the same codec.
     *
     * @param string  $outputPathfile
     * @param bool    $streamCopy
     *
     * @return Concat
     *
     * @throws RuntimeException
     */
    public function saveFromSameCodecs($outputPathfile, $streamCopy = TRUE)
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

        if($fileStream === false) {
            throw new RuntimeException('Cannot open the temporary file.');
        }

        $count_videos = 0;
        if(is_array($this->sources) && (count($this->sources) > 0)) {
            foreach ($this->sources as $videoPath) {
                $line = "";

                if($count_videos != 0)
                    $line .= "\n";

                $line .= "file " . addcslashes($videoPath, '\'"\\\0 ');

                fwrite($fileStream, $line);

                $count_videos++;
            }
        }
        else {
            throw new InvalidArgumentException('The list of videos is not a valid array.');
        }
        fclose($fileStream);


        $commands = array(
            '-f', 'concat', '-safe', '0',
            '-i', $sourcesFile
        );

        // Check if stream copy is activated
        if($streamCopy === TRUE) {
            $commands[] = '-c';
            $commands[] = 'copy';
        }

        // If not, we can apply filters
        else {
            foreach ($this->filters as $filter) {
                $commands = array_merge($commands, $filter->apply($this));
            }
        }

        // Set the output file in the command
        $commands = array_merge($commands, array($outputPathfile));

        // Execute the command
        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($outputPathfile);
            // TODO@v1: paste this line into an `finally` block.
            $this->cleanupTemporaryFile($sourcesFile);
            throw new RuntimeException('Unable to save concatenated video', $e->getCode(), $e);
        }

        $this->cleanupTemporaryFile($sourcesFile);
        return $this;
    }

    /**
     * Saves the concatenated video in the given filename, considering that the sources videos are all encoded with the same codec.
     *
     * @param string  $outputPathfile
     *
     * @return Concat
     *
     * @throws RuntimeException
     */
    public function saveFromDifferentCodecs(FormatInterface $format, $outputPathfile)
    {
        /**
         * @see https://ffmpeg.org/ffmpeg-formats.html#concat
         * @see https://trac.ffmpeg.org/wiki/Concatenate
         */

        // Check the validity of the parameter
        if(!is_array($this->sources) || (count($this->sources) == 0)) {
            throw new InvalidArgumentException('The list of videos is not a valid array.');
        }

        // Create the commands variable
        $commands = array();

        // Prepare the parameters
        $nbSources = 0;
        $files = array();

        // For each source, check if this is a legit file
        // and prepare the parameters
        foreach ($this->sources as $videoPath) {
            $files[] = '-i';
            $files[] = $videoPath;
            $nbSources++;
        }

        $commands = array_merge($commands, $files);

        // Set the parameters of the request
        $commands[] = '-filter_complex';

        $complex_filter = '';
        for($i=0; $i<$nbSources; $i++) {
            $complex_filter .= '['.$i.':v:0] ['.$i.':a:0] ';
        }
        $complex_filter .= 'concat=n='.$nbSources.':v=1:a=1 [v] [a]';

        $commands[] = $complex_filter;
        $commands[] = '-map';
        $commands[] = '[v]';
        $commands[] = '-map';
        $commands[] = '[a]';

        // Prepare the filters
        $filters = clone $this->filters;
        $filters->add(new SimpleFilter($format->getExtraParams(), 10));

        if ($this->driver->getConfiguration()->has('ffmpeg.threads')) {
            $filters->add(new SimpleFilter(array('-threads', $this->driver->getConfiguration()->get('ffmpeg.threads'))));
        }
        if ($format instanceof VideoInterface) {
            if (null !== $format->getVideoCodec()) {
                $filters->add(new SimpleFilter(array('-vcodec', $format->getVideoCodec())));
            }
        }
        if ($format instanceof AudioInterface) {
            if (null !== $format->getAudioCodec()) {
                $filters->add(new SimpleFilter(array('-acodec', $format->getAudioCodec())));
            }
        }

        // Add the filters
        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        if ($format instanceof AudioInterface) {
            if (null !== $format->getAudioKiloBitrate()) {
                $commands[] = '-b:a';
                $commands[] = $format->getAudioKiloBitrate() . 'k';
            }
            if (null !== $format->getAudioChannels()) {
                $commands[] = '-ac';
                $commands[] = $format->getAudioChannels();
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

        $failure = null;

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('Encoding failed', $e->getCode(), $e);
        }

        return $this;
    }
}
