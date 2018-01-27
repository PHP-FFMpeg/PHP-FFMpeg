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

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

/**
 * Synchronizes audio and video in case of desynchronized movies.
 */
class SynchronizeFilter implements VideoFilterInterface
{

    use TPriorityFilter;

    /**
     * @var int
     */
    private $priority;

    public function __construct(int $priority = 12)
    {
        $this->setPriority($priority);
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format): array
    {
        return ['-async', '1', '-metadata:s:v:0', 'start_time=0'];
    }
}
