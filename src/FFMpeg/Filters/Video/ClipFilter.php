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

class ClipFilter implements VideoFilterInterface
{
    /** @var TimeCode */
    private $start;
    /** @var TimeCode */
    private $duration;
    /** @var integer */
    private $priority;

    public function __construct(TimeCode $start, TimeCode $duration, $priority = 0)
    {
    	$this->start = $start;
    	$this->duration = $duration;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return TimeCode
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return TimeCode
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
    	return array('-ss', $this->start->__toString(), '-t', $this->duration->__toString());
    }
}
