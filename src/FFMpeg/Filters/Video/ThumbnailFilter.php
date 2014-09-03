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

use FFMpeg\Filters\VideoFilterInterface;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

/**
 * Class ThumbnailFilter
 *
 */
class ThumbnailFilter implements VideoFilterInterface
{
    /**
     * @param     $videoFile
     * @param     $duration
     * @param int $count
     * @param int $priority
     */
    public function __construct(array $params, $priority = 0)
    {
        $this->params = $params;
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param Video          $video
     * @param VideoInterface $format
     * @return array
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $videoDuration = $video->getStreams()->videos()->first()->get('duration');

        // Avoid making duplicate thumbnails
        $times = array_unique(array_merge(
            $this->params['thumbnail_times'],
            $this->getIntervalsOrNumber($videoDuration)
        ));

        $commandArguments = [];

        // Since array_unique preserves the keys, we need 
        // to re-index the $times array
        foreach (array_values($times) as $index => $time) {
            $commandArguments = array_merge(
                $commandArguments, 
                ['-ss', $time, '-f', 'image2', '-vframes', '1', 'thumb'.$index.'.png']
            );
        }

        return $commandArguments;
    }

    /**
     * @param $videoDuration
     * @return array
     */
    protected function getIntervalsOrNumber($videoDuration)
    {
        // TODO later we will allow both 
        return (isset($this->params['thumbnail_interval']))
            ? $this->getIntervals($videoDuration)
            : $this->getNumber($videoDuration)
        ;
    }

    /**
     * @return array
     */
    protected function getIntervals($videoDuration)
    {
        if (!$this->params['thumbnail_interval']) {
            return [];
        }

        $interval = $this->params['thumbnail_interval'];
        $start    = isset($this->params['thumbnail_first']) ? 0 : $interval;
        $times    = range($start, $videoDuration, $interval);

        return $times;
    }

    /**
     * @param $videoDuration
     * @return array
     */
    protected function getNumber($videoDuration)
    {
        if (!$this->params['thumbnail_number']) {
            return [];
        }

        $divider  = $this->params['thumbnail_number'];
        if (!$this->params['thumbnail_first']) {
            ++$divider;
        }

        $period  = $videoDuration / $divider;
        $times = range(0, $videoDuration-1, $period);

        if (!$this->params['thumbnail_first']) {
            array_shift($times);
        }

        return $times;
    }
}

