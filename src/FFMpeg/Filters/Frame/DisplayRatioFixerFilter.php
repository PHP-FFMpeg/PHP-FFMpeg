<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Frame;

use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Frame;
use FFMpeg\Filters\TPriorityFilter;

class DisplayRatioFixerFilter implements FrameFilterInterface
{

    use TPriorityFilter;

    /**
     * @var int
     */
    private $priority;

    public function __construct(int $priority = 0)
    {
        $this->setPriority($priority);
    }

    /**
     * @inheritDoc
     */
    public function apply(Frame $frame): array
    {
        $dimensions = null;
        $commands = [];

        foreach ($frame->getVideo()->getStreams()->getVideoStreams() as $stream) {
            try {
                $dimensions = $stream->getDimensions();
                $commands[] = '-s';
                $commands[] = $dimensions->getWidth() . 'x' . $dimensions->getHeight();
                break;
            } catch (RuntimeException $e) {
                // ignore error when getting the dimensions
            }
        }

        return $commands;
    }
}
