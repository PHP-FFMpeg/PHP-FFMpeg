<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;
use FFMpeg\Coordinate\TimeCode;

/**
 * Generate Preview for video
 */
class PreviewFilter implements VideoFilterInterface
{

    private $priority;
    private $start;
    private $duration;

    public function __construct(TimeCode $timecode_start,
            TimeCode $timecode_end = null, $priority = 12)
    {
        $this->priority = $priority;

        $this->start = $timecode_start;

        if(isset($timecode_end) && !empty($timecode_end))
        {
            $this->duration = $timecode_end->toInt() - $timecode_start->toInt();
        }
        else
        {
            $this->duration = 60;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        return array('-ss', (string) $this->start,
            '-t', $this->duration);
    }

}
