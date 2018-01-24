<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters;

/**
 * Default interface for stream filters
 */
interface FilterInterface
{

    /**
     * Returns the priority of the filter.
     *
     * @return int
     */
    public function setPriority(int $priority = 0): void;

    /**
     * Returns the current priority
     *
     * @return int
     */
    public function getPriority(): int;
}
