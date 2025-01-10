<?php

namespace FFMpeg\Media;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Format\FormatInterface;

/**
 * Video clip.
 *
 * Use input seeking, see http://trac.ffmpeg.org/wiki/Seeking
 */
class Clip extends Video
{
    /** @var TimeCode Start time */
    private $start;

    /** @var TimeCode Duration */
    private $duration;

    /** @var Video Parrent video */
    private $video;

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $start, ?TimeCode $duration = null)
    {
        $this->start = $start;
        $this->duration = $duration;
        $this->video = $video;

        parent::__construct($video->getPathfile(), $driver, $ffprobe);
    }

    /**
     * Returns the video related to the frame.
     *
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Return base part of command.
     *
     * @return array
     */
    protected function basePartOfCommand(FormatInterface $format)
    {
        $arr = ['-y', '-ss', (string) $this->start, '-i', $this->pathfile];

        if (false === is_null($this->duration)) {
            $arr[] = '-t';
            $arr[] = (string) $this->duration;
        }

        return $arr;
    }
}
