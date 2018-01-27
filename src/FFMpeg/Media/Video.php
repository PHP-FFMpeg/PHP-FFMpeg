<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Filters\Video\VideoFilterInterface;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\VideoInterface;
use Neutron\TemporaryFilesystem\Manager as FsManager;

class Video extends Audio implements MediaTypeInterface
{

    /**
     * FileSystem Manager instance
     *
     * @var Manager
     */
    protected $fs;

    /**
     * FileSystem Manager ID
     *
     * @var int
     */
    protected $fsId;

    /**
     * @inheritDoc
     *
     * @return VideoFilters
     */
    public function filters()
    {
        return new VideoFilters($this);
    }

    /**
     * @inheritDoc
     *
     * @return Video
     */
    public function addFilter(FilterInterface $filter): MediaTypeInterface
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * Exports the video in the desired format, applies registered filters.
     *
     * @param  FormatInterface $format
     * @param  string          $outputPathfile
     * @return Video
     * @throws RuntimeException
     */
    public function save(FormatInterface $format, string $outputPathfile)
    {
        $passes = $this->buildCommand($format, $outputPathfile);

        $failure = null;
        $totalPasses = $format->getPasses();

        foreach ($passes as $pass => $passCommands) {
            try {
                // add listeners here
                $listeners = null;

                if ($format instanceof ProgressableInterface) {
                    $listeners = $format->createProgressListener($this, $this->ffprobe, $pass + 1, $totalPasses);
                }

                $this->driver->command($passCommands, false, $listeners);
            } catch (ExecutionFailureException $e) {
                $failure = $e;
                break;
            }
        }

        $this->fs->clean($this->fsId);

        if ($failure !== null) {
            throw new RuntimeException('Encoding failed', $failure->getCode(), $failure);
        }

        return $this;
    }

    /**
     * NOTE: This method is different to the Audio's one, because Video is using passes.
     *
     * @inheritDoc
     */
    public function getFinalCommand(FormatInterface $format, string $outputPathfile)
    {
        $finalCommands = [];

        foreach ($this->buildCommand($format, $outputPathfile) as $pass => $passCommands) {
            $finalCommands[] = implode(' ', $passCommands);
        }

        $this->fs->clean($this->fsId);

        return $finalCommands;
    }

    /**
     * **NOTE:** This creates passes instead of a single command!
     *
     * @inheritDoc
     * @return     string[][]
     */
    protected function buildCommand(FormatInterface $format, string $outputPathfile)
    {
        $commands = ['-y', '-i', $this->pathfile];

        $filters = clone $this->filters;
        $filters->add(new SimpleFilter($format->getExtraParams(), 10));

        $filters->add(new SimpleFilter(['-threads', (string) $this->driver->getConfiguration()->get('ffmpeg.threads', 2)]));

        if ($format instanceof VideoInterface && $format->getVideoCodec() !== null) {
            // TODO: Write tests for this behaviour.
            if ($this->ffprobe->getCodecTester()->has($format->getVideoCodec())) {
                // hit!
                $filters->add(new SimpleFilter(['-vcodec', $format->getVideoCodec()]));
            } else {
                // miss! Default codec is not supported, search for supported ones and take the first one which is supported.
                $availableVideoCodec = null;

                foreach ($format->getAvailableVideoCodecs() as $videoCodec) {
                    if ($this->ffprobe->getCodecTester()->has($videoCodec)) {
                        // hit!
                        $availableVideoCodec = $videoCodec;
                        break;
                    }
                }

                if ($availableVideoCodec === null) {
                    throw new RuntimeException('No codecs supported by the format ' . get_class($format) . ' are also supported by your host system.');
                } else {
                    $filters->add(new SimpleFilter(['-vcodec', $availableVideoCodec]));
                }
            }
        }

        if ($format instanceof AudioInterface && $format->getAudioCodec() !== null) {
            // TODO: Write tests for this behaviour.
            if ($this->ffprobe->getCodecTester()->has($format->getAudioCodec())) {
                // hit!
                $filters->add(new SimpleFilter(['-acodec', $format->getAudioCodec()]));
            } else {
                // miss! Default codec is not supported, search for supported ones and take the first one which is supported.
                $availableAudioCodec = null;

                foreach ($format->getAvailableAudioCodecs() as $audioCodec) {
                    if ($this->ffprobe->getCodecTester()->has($audioCodec)) {
                        // hit!
                        $availableAudioCodec = $audioCodec;
                        break;
                    }
                }

                if ($availableAudioCodec === null) {
                    throw new RuntimeException('No codecs supported by the format ' . get_class($format) . ' are also supported by your host system.');
                } else {
                    $filters->add(new SimpleFilter(['-acodec', $availableAudioCodec]));
                }
            }
        }

        // apply filters
        foreach ($filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this, $format));
        }

        if ($format instanceof VideoInterface) {
            // TODO: Remove some hardcoded values
            $commands[] = '-b:v';
            $commands[] = ($format->getKiloBitrate() ? $format->getKiloBitrate() : '') . 'k';
            $commands[] = '-refs';
            $commands[] = '6';
            $commands[] = '-coder';
            $commands[] = '1';
            $commands[] = '-sc_threshold';
            $commands[] = '40';
            $commands[] = '-flags';
            $commands[] = '+loop';
            $commands[] = '-me_range';
            $commands[] = '16';
            $commands[] = '-subq';
            $commands[] = '7';
            $commands[] = '-i_qfactor';
            $commands[] = '0.71';
            $commands[] = '-qcomp';
            $commands[] = '0.6';
            $commands[] = '-qdiff';
            $commands[] = '4';
            $commands[] = '-trellis';
            $commands[] = '1';
        }

        if ($format instanceof AudioInterface) {
            if ($format->getAudioKiloBitrate()) {
                $commands[] = '-b:a';
                $commands[] = $format->getAudioKiloBitrate() . 'k';
            }
            if ($format->getAudioChannels() !== null) {
                $commands[] = '-ac';
                $commands[] = (string) $format->getAudioChannels();
            }
        }

        // add additional parameters if the user passed some
        if ($format instanceof VideoInterface && $format->getAdditionalParameters() !== null) {
            foreach ($format->getAdditionalParameters() as $additionalParameter) {
                $commands[] = $additionalParameter;
            }
        }

        // Merge Filters into one command
        $videoFilterVars = $videoFilterProcesses = [];

        // phpcs:disable Generic.CodeAnalysis.ForLoopWithTestFunctionCall
        for ($i = 0; $i < count($commands); $i++) {
            // phpcs:enable
            $command = $commands[$i];

            // continue when it is not a video filter
            if ($command !== '-vf') {
                continue;
            }

            $commandSplits = explode(";", $commands[$i + 1]);
            if (count($commandSplits) === 1) {
                $commandSplit = $commandSplits[0];
                $command = trim($commandSplit);
                if (preg_match("/^\[in\](.*?)\[out\]$/is", $command, $match)) {
                    $videoFilterProcesses[] = $match[1];
                } else {
                    $videoFilterProcesses[] = $command;
                }
            } else {
                foreach ($commandSplits as $commandSplit) {
                    $command = trim($commandSplit);
                    if (preg_match("/^\[[^\]]+\](.*?)\[[^\]]+\]$/is", $command, $match)) {
                        $videoFilterProcesses[] = $match[1];
                    } else {
                        $videoFilterVars[] = $command;
                    }
                }
            }

            unset($commands[$i]);
            unset($commands[$i + 1]);
            $i++;
        }

        $videoFilterCommands = $videoFilterVars;
        $lastInput = 'in';
        foreach ($videoFilterProcesses as $i => $process) {
            $command = '[' . $lastInput .']';
            $command .= $process;
            $lastInput = 'p' . $i;
            if ($i === (count($videoFilterProcesses) - 1)) {
                $command .= '[out]';
            } else {
                $command .= '[' . $lastInput . ']';
            }

            $videoFilterCommands[] = $command;
        }
        $videoFilterCommand = implode(';', $videoFilterCommands);

        if ($videoFilterCommand) {
            $commands[] = '-vf';
            $commands[] = $videoFilterCommand;
        }

        $this->fs = FsManager::create();
        $this->fsId = uniqid('ffmpeg-passes');
        $passPrefix = $this->fs->createTemporaryDirectory(0777, 50, $this->fsId) . '/' . uniqid('pass-');
        $passes = [];
        $totalPasses = $format->getPasses();

        if (!$totalPasses) {
            throw new InvalidArgumentException('Pass number should be a positive value.');
        }

        for ($i = 1; $i <= $totalPasses; $i++) {
            $pass = $commands;

            if ($totalPasses > 1) {
                $pass[] = '-pass';
                $pass[] = $i;
                $pass[] = '-passlogfile';
                $pass[] = $passPrefix;
            }

            $pass[] = $outputPathfile;

            $passes[] = $pass;
        }

        return $passes;
    }

    /**
     * Gets the frame at timecode.
     *
     * @param  TimeCode $at
     * @return Frame
     */
    public function frame(TimeCode $at): Frame
    {
        return new Frame($this, $this->driver, $this->ffprobe, $at);
    }

    /**
     * Extracts a gif from a sequence of the video.
     *
     * @param  TimeCode  $at
     * @param  Dimension $dimension
     * @param  integer   $duration
     * @return Gif
     */
    public function gif(TimeCode $at, Dimension $dimension, $duration = null): Gif
    {
        return new Gif($this, $this->driver, $this->ffprobe, $at, $dimension, $duration);
    }

    /**
     * Concatenates a list of videos into one unique video.
     *
     * @param  array $sources
     * @return Concat
     */
    public function concat($sources): Concat
    {
        return new Concat($sources, $this->driver, $this->ffprobe);
    }
}
