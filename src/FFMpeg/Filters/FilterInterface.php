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

namespace FFMpeg\Filters;

interface FilterInterface
{
    /**
     * Returns the priority of the filter.
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * Sets the given priority for this filter. A higher priority(~ tinier number) means that
     * this filter will be more likely at the start point of the generated command
     * that ffmpeg/ffprobe will receive.
     *
     * Examples:
     * Filter A has priority of 9.
     * Filter B has priority of 3.
     *
     * In the final command, the subcommand of B would be placed before A.
     *
     * @param  int $priority
     * @return void
     */
    public function setPriority(int $priority = 0): void;
}
