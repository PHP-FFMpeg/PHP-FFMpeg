<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <romain@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class ExtractMultipleFramesFilter implements VideoFilterInterface {
    /** will extract a frame every second */
    const FRAMERATE_EVERY_SEC = '1/1';
    /** will extract a frame every 2 seconds */
    const FRAMERATE_EVERY_2SEC = '1/2';
    /** will extract a frame every 5 seconds */
    const FRAMERATE_EVERY_5SEC = '1/5';
    /** will extract a frame every 10 seconds */
    const FRAMERATE_EVERY_10SEC = '1/10';
    /** will extract a frame every 30 seconds */
    const FRAMERATE_EVERY_30SEC = '1/30';
    /** will extract a frame every minute */
    const FRAMERATE_EVERY_60SEC = '1/60';

    /**
     * @var string
     */
    private $frameRate;

    /**
     * @var int
     */
    private $priority;

    /**
     * @param $frameRate
     * @param selfFRAMERATE_EVERY_SEC $frameFileType
     */
    public function __construct($frameRate = self::FRAMERATE_EVERY_SEC, $priority = 0) {
        $this->frameRate = $frameRate;
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format) {
        $commands = [];
        $commands[] = '-vf';
        $commands[] = 'fps=' . $this->frameRate;
        return $commands;
    }
}
