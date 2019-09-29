<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Frame;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Frame;

class DisplayRatioFixerFilter implements FrameFilterInterface
{
    use TPriorityFilter;

    /** @var int */
    private $priority;

    public function __construct(int $priority = 0)
    {
        $this->priority = $priority;
    }

    /**
     * @inheritDoc
     */
    public function apply(Frame $frame): array
    {
        $commands = [];

        foreach ($frame->getVideo()->getStreams() as $stream) {
            if ($stream->isVideo()) {
                try {
                    $dimensions = $stream->getDimensions();
                    $commands[] = '-s';
                    $commands[] = $dimensions->getWidth() . 'x' . $dimensions->getHeight();
                    break;
                } catch (RuntimeException $e) {
                    // ignore
                }
            }
        }

        return $commands;
    }
}
