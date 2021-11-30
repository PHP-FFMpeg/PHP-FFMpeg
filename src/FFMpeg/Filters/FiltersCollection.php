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

use Traversable;

class FiltersCollection implements \Countable, \IteratorAggregate
{
    private $sorted;
    private $filters = [];

    /**
     * @return FiltersCollection
     */
    public function add(FilterInterface $filter)
    {
        $this->filters[$filter->getPriority()][] = $filter;
        $this->sorted                            = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        if (0 === count($this->filters)) {
            return 0;
        }

        return count(call_user_func_array('array_merge', array_values($this->filters)));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        if (null === $this->sorted) {
            if (0 === count($this->filters)) {
                $this->sorted = $this->filters;
            } else {
                krsort($this->filters);
                $this->sorted = call_user_func_array('array_merge', array_values($this->filters));
            }
        }

        return new \ArrayIterator($this->sorted);
    }
}
