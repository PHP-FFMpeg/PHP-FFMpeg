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
use FFMpeg\Format\ProgressListener\VideoProgressListener;

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
     *
     * @return Video
     *
     * @throws RuntimeException
     */
    public function save(FormatInterface $format, $outputPathfile)
    {
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

        $encodingOptions = array();
        foreach ($filters as $filter) {
            $encodingOptions = array_merge($encodingOptions, $filter->apply($this, $format));
        }

        if ($format instanceof VideoInterface) {
            $encodingOptions[] = '-b:v';
            $encodingOptions[] = $format->getKiloBitrate() . 'k';
            $encodingOptions[] = '-refs';
            $encodingOptions[] = '6';
            $encodingOptions[] = '-coder';
            $encodingOptions[] = '1';
            $encodingOptions[] = '-sc_threshold';
            $encodingOptions[] = '40';
            $encodingOptions[] = '-flags';
            $encodingOptions[] = '+loop';
            $encodingOptions[] = '-me_range';
            $encodingOptions[] = '16';
            $encodingOptions[] = '-subq';
            $encodingOptions[] = '7';
            $encodingOptions[] = '-i_qfactor';
            $encodingOptions[] = '0.71';
            $encodingOptions[] = '-qcomp';
            $encodingOptions[] = '0.6';
            $encodingOptions[] = '-qdiff';
            $encodingOptions[] = '4';
            $encodingOptions[] = '-trellis';
            $encodingOptions[] = '1';
        }

        if ($format instanceof AudioInterface) {
            if (null !== $format->getAudioKiloBitrate()) {
                $encodingOptions[] = '-b:a';
                $encodingOptions[] = $format->getAudioKiloBitrate() . 'k';
            }
            if (null !== $format->getAudioChannels()) {
                $encodingOptions[] = '-ac';
                $encodingOptions[] = $format->getAudioChannels();
            }
        }

        return $this->transcode($outputPathfile, $encodingOptions, $format->getPasses(), $format);
    }

    /**
     * Exports the video using manually provided encoding options
     *
     * @param string                     $outputPathfile
     * @param array                      $encodingOptions
     * @param number                     $totalPasses
     * @param FormatInterface|callable   $progressable
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return Video
     */
    public function transcode($outputPathfile, array $encodingOptions, $totalPasses = 1, $progressable = null)
    {
        $options = array_merge(array('-y', '-i', $this->pathfile), $encodingOptions);

        if ($totalPasses < 1) {
            throw new InvalidArgumentException('Pass number should be a positive value.');
        }

        $commands = array();
        if ($totalPasses > 1) {
            $fs = FsManager::create();
            $fsId = uniqid('ffmpeg-passes');
            $passPrefix = $fs->createTemporaryDirectory(0777, 50, $fsId) . '/' . uniqid('pass-');
        }

        $failure = null;
        for ($i = 0; $i < $totalPasses; $i++) {
            $passNumber = $i + 1;
            $command = $options;
            if ($totalPasses > 1) {
                $command = array_merge($command, array(
                    '-pass', "$passNumber", '-passlogfile', $passPrefix
                ));
            }
            $command[] = $outputPathfile;

            try {
                $listeners = $this->getListeners($progressable, $passNumber, $totalPasses);
                $this->driver->command($command, false, $listeners);

            } catch (ExecutionFailureException $e) {
                $failure = $e;
                break;
            }
        }

        if ($totalPasses > 1) {
            $fs->clean($fsId);
        }

        if ($failure) {
            throw new RuntimeException('Encoding failed', $failure->getCode(), $failure);
        }

        return $this;
    }

    private function getListeners($progressable, $passNumber, $totalPasses)
    {
        if ($progressable instanceof ProgressableInterface) {
            return $progressable->createProgressListener($this, $this->ffprobe, $passNumber, $totalPasses);

        } elseif (is_callable($progressable)) {
            $media = $this;
            $listener = new VideoProgressListener($this->ffprobe, $this->pathfile, $passNumber, $totalPasses);
            $listener->on('progress', function() use ($progressable, $media) {
                call_user_func_array($progressable, array_merge(array($media), func_get_args()));
            });

            return array($listener);
        }

        return null;
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
