<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video\Combined;

use FFMpeg\Coordinate\Point;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\FilterInterface;
use FFMpeg\Filters\FilterChain;
use FFMpeg\Filters\FilterGraph;
use FFMpeg\Filters\Video\MovieFilter;
use FFMpeg\Filters\Video\OverlayFilter;

class WatermarkFilter implements FilterInterface
{
    const TOP_LEFT      = 1;
    const TOP_RIGHT     = 2;
    const BOTTOM_LEFT   = 3;
    const BOTTOM_RIGHT  = 4;

    private $path;
    private $x;
    private $y;
    private $position;
    private $offset = 10;

    public function __construct($path, $x = 0, $y = 0)
    {
        if (false === is_file($path)) {
            throw new InvalidArgumentException(sprintf('%s is not a file', $path));
        }

        $this->path = $path;
        $this->x = $x;
        $this->y = $y;
    }

    public function getName()
    {
        return 'watermark';
    }

    public function setCorner($position, $offset = 10)
    {
        if (false === in_array($position, array(
            self::TOP_LEFT,
            self::TOP_RIGHT,
            self::BOTTOM_LEFT,
            self::BOTTOM_RIGHT
        ))) {
            throw new InvalidArgumentException('Position must be one of the following values WatermarkFilter::TOP_LEFT, WatermarkFilter::TOP_RIGHT, WatermarkFilter::BOTTOM_LEFT, WatermarkFilter::BOTTOM_LEFT.');
        }
        $this->position = $position;
        $this->offset = $offset;

        return $this;
    }

    private function getFilterChains()
    {
        $overlay = new OverlayFilter($this->x, $this->y);

        switch($this->position) {
            case self::TOP_LEFT:
                $overlay->topLeftCorner($this->offset);
                break;
            case self::TOP_RIGHT:
                $overlay->topRightCorner($this->offset);
                break;
            case self::BOTTOM_LEFT:
                $overlay->bottomLeftCorner($this->offset);
                break;
            case self::BOTTOM_RIGHT:
                $overlay->bottomRightCorner();
                break;
        }

        $movie = new MovieFilter($this->path);

        $chainLogo = new FilterChain();
        $chainLogo->addFilter($movie);
        $chainLogo->addOutputLink('watermark');

        $chainOverlay = new FilterChain();
        $chainOverlay->addFilter($overlay);
        $chainOverlay->addInputLink(array('watermark', 'in'));
        $chainOverlay->addOutputLink('out');

        return array($chainLogo, $chainOverlay);
    }

    public function __toString()
    {
        $graph = new FilterGraph();

        foreach ($this->getFilterChains() as $chain) {
            $graph->addFilterChain($chain);
        }

        return (string) $graph;
    }
}
