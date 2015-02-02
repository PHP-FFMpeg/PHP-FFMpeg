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
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\VideoInterface;
use Neutron\TemporaryFilesystem\Manager as FsManager;

class Video extends Audio
{
    /**
     * {@inheritdoc}
     *
     * @return VideoFilters
     */
    public function filters()
    {
        return new VideoFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Video
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * Exports the video in the desired format, applies registered filters.
     *
     * @param FormatInterface $format
     * @param string          $outputPathfile
     * @param array           $additionalOptions
     *
     * @return Video
     *
     * @throws RuntimeException
     */
    public function save(FormatInterface $format, $outputPathfile, array $additionalOptions = array())
    {
        $commands = array('-y', '-i', $this->pathfile);

        $filters = clone $this->filters;

        if ($this->driver->getConfiguration()->has('ffmpeg.threads')) {
            $filters->add(new SimpleFilter(array('-threads', $this->driver->getConfiguration()->get('ffmpeg.threads'))));
        }
        if ($format instanceof AudioInterface) {
            if (null !== $format->getAudioCodec()) {
                $filters->add(new SimpleFilter(array('-acodec', $format->getAudioCodec())));
            }
            if (null !== $format->getAudioKiloBitrate()) {
                $filters->add(new SimpleFilter(array('-b:a', $format->getAudioKiloBitrate().'k')));
            }
            if (null !== $format->getAudioChannels()) {
                $filters->add(new SimpleFilter(array('-ac', $format->getAudioChannels())));
            }
        }
        if ($format instanceof VideoInterface) {
            if (null !== $format->getVideoCodec()) {
                $filters->add(new SimpleFilter(array('-vcodec', $format->getVideoCodec())));
            }
            if (null !== $format->getKiloBitrate()) {
                $filters->add(new SimpleFilter(array('-b:v', $format->getKiloBitrate().'k')));
            }
        }
        $filters->add(new SimpleFilter($additionalOptions));
        $filters->add(new SimpleFilter($format->getExtraParams(), 10));

        foreach ($filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this, $format));
        }

        $fs = FsManager::create();
        $fsId = uniqid('ffmpeg-passes');
        $passPrefix = $fs->createTemporaryDirectory(0777, 50, $fsId) . '/' . uniqid('pass-');
        $passes = array();
        $totalPasses = $format->getPasses();

        if (1 > $totalPasses) {
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

        $failure = null;

        foreach ($passes as $pass => $passCommands) {
            try {
                /** add listeners here */
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

        $fs->clean($fsId);

        if (null !== $failure) {
            throw new RuntimeException('Encoding failed', $failure->getCode(), $failure);
        }

        return $this;
    }

    /**
     * Gets the frame at timecode.
     *
     * @param  TimeCode $at
     * @return Frame
     */
    public function frame(TimeCode $at)
    {
        return new Frame($this, $this->driver, $this->ffprobe, $at);
    }
}
