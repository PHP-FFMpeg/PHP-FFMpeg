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
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Media\Frame;

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
        $commands = array('-y', '-i', $this->pathfile);

        $filters = clone $this->filters;
        $filters->add(new SimpleFilter($format->getExtraParams(), 10));

        if ($this->driver->getConfiguration()->has('ffmpeg.threads')) {
            $filters->add(new SimpleFilter(array('-threads', $this->driver->getConfiguration()->get('ffmpeg.threads'))));
        }
        if (null !== $format->getVideoCodec()) {
            $filters->add(new SimpleFilter(array('-vcodec', $format->getVideoCodec())));
        }
        if (null !== $format->getAudioCodec()) {
            $filters->add(new SimpleFilter(array('-acodec', $format->getAudioCodec())));
        }

        foreach ($filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this, $format));
        }

        $commands[] = '-b:v';
        $commands[] = $format->getKiloBitrate() . 'k';
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

        if (null !== $format->getAudioKiloBitrate()) {
            $commands[] = '-b:a';
            $commands[] = $format->getAudioKiloBitrate() . 'k';
        }

        $passPrefix = uniqid('pass-');

        $pass1 = $commands;
        $pass2 = $commands;

        $pass1[] = '-pass';
        $pass1[] = '1';
        $pass1[] = '-passlogfile';
        $pass1[] = $passPrefix;
        $pass1[] = $outputPathfile;

        $pass2[] = '-pass';
        $pass2[] = '2';
        $pass2[] = '-passlogfile';
        $pass2[] = $passPrefix;
        $pass2[] = $outputPathfile;

        $failure = null;

        foreach (array($pass1, $pass2) as $pass => $passCommands) {
            try {
                /** add listeners here */
                $listeners = null;

                if ($format instanceof ProgressableInterface) {
                    $listeners = $format->createProgressListener($this, $this->ffprobe, $pass + 1, 2);
                }

                $this->driver->command($passCommands, false, $listeners);
            } catch (ExecutionFailureException $e) {
                $failure = $e;
                break;
            }
        }

        $this
            ->cleanupTemporaryFile(getcwd() . '/' . $passPrefix . '-0.log')
            ->cleanupTemporaryFile(getcwd() . '/' . $passPrefix . '-0.log')
            ->cleanupTemporaryFile(getcwd() . '/' . $passPrefix . '-0.log.mbtree');

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
        return new Frame($this->pathfile, $this->driver, $this->ffprobe, $at);
    }
}
