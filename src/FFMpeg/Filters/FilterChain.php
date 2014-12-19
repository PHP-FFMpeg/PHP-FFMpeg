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

use FFMpeg\Exception\LogicException;

class FilterChain
{
    public $filters = array();
    public $inputLinks = array();
    public $outputLinks = array();

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function addInputLink($link)
    {
        foreach((array) $link as $l) {
            $this->inputLinks[] = (string) $l;
        }
    }

    public function addOutputLink($link)
    {
        foreach((array) $link as $l) {
            $this->outputLinks[] = (string) $l;
        }
    }

    private function formatLink(array $links)
    {
        return implode('', array_map(function($links){ return '['.$links.']';}, array_filter($links)));
    }

    private function formatInputLink()
    {
        $inputLink = $this->formatLink($this->inputLinks);

        if ('' === $inputLink) {
            return '';
        }

        return sprintf('%s ', $inputLink);
    }

    private function formatOutputLink()
    {
        $outputLink = $this->formatLink($this->outputLinks);

        if ('' === $outputLink) {
            return '';
        }

        return sprintf(' %s', $outputLink);
    }

    private function formatFilterChain()
    {
        $filters = array_filter($this->filters);
        if (count($filters) === 0) {
            throw new LogicException('A filter chain must contain at least one filter.');
        }
        return implode(', ', array_map('strval', $filters));
    }

    public function __toString()
    {
        return sprintf('%s%s%s', $this->formatInputLink(), $this->formatFilterChain(), $this->formatOutputLink());
    }
}
