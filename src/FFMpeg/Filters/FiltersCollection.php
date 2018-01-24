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

    /**
     * @var FilterInterface[]|null
     */
    private $sorted;

    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * @param FilterInterface $filter
     *
     * @return self
     */
    public function add(FilterInterface $filter): self
    {
        $this->filters[$filter->getPriority()][] = $filter;
        $this->sorted = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        if (count($this->filters) === 0) {
            return 0;
        }

        return count(call_user_func_array('array_merge', $this->filters));
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \Traversable
    {
        if ($this->sorted === null) {
            if (count($this->filters) === 0) {
                $this->sorted = $this->filters;
            } else {
                krsort($this->filters);
                $this->sorted = call_user_func_array('array_merge', $this->filters);
            }
        }

        return new \ArrayIterator($this->sorted);
    }
}
