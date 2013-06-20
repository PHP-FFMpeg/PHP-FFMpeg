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

class FiltersCollection implements \Countable, \IteratorAggregate
{
    private $filters = array();

    public function add(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function count()
    {
        return count($this->filters);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->filters);
    }
}
