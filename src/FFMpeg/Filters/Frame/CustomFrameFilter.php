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

class CustomFrameFilter implements FrameFilterInterface
{
    use TPriorityFilter;

    /** @var string */
    private $filter;
    /** @var int */
    private $priority;

    /**
     * A custom filter, useful if you want to build complex filters
     *
     * @param string $filter
     * @param int    $priority
     */
    public function __construct(string $filter, int $priority = 0)
    {
        $this->filter = $filter;
        $this->priority = $priority;
    }

    /**
     * @inheritDoc
     */
    public function apply(Frame $frame): array
    {
        return ['-vf', $this->filter];
    }
}
