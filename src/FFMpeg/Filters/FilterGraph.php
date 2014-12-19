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

class FilterGraph
{
    public $chains = array();

    public function addFilterChain(FilterChain $chain)
    {
        $this->chains[] = $chain;
    }

    public function __toString()
    {
        return implode('; ', array_map('strval', array_filter($this->chains)));
    }
}
