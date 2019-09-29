<?php

declare(strict_types=1);

namespace FFMpeg\Media;

use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;

/**
 * Video clip.
 *
 * Use input seeking, see http://trac.ffmpeg.org/wiki/Seeking
 */
class Clip extends Video implements IVideoOwned
{
    use TVideoOwned;

    /** @var TimeCode Start time */
    private $start;

    /** @var TimeCode Duration */
    private $duration;

    /** @var Video Parrent video */
    private $video;

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $start, TimeCode $duration = null)
    {
        $this->start = $start;
        $this->duration = $duration;
        $this->video = $video;

        parent::__construct($video->getPathfile(), $driver, $ffprobe);
    }

    /**
     * Return base part of command.
     *
     * @return array
     */
    protected function basePartOfCommand()
    {
        $arr = ['-y', '-ss', (string) $this->start, '-i', $this->pathfile];

        if (null !== $this->duration) {
            $arr[] = '-t';
            $arr[] = (string) $this->duration;
        }

        return $arr;
    }
}
