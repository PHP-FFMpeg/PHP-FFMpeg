<?php

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
