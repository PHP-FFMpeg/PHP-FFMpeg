<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Filters\AbstractFilter;

class OverlayFilter extends AbstractFilter implements VideoFilterInterface
{
    public function __construct($x = 0, $y = 0)
    {
        parent::__construct('overlay', array($x, $y));
    }

    public function topLeftCorner($offset = 10)
    {
        $this->args = array($offset, $offset);
    }

    public function topRightCorner($offset = 10)
    {
        $this->args = array('main_w-overlay_w-'.$offset, $offset);
    }

    public function bottomRightCorner($offset = 10)
    {
        $this->args = array('main_w-overlay_w-'.$offset, 'main_h-overlay_h-'.$offset);
    }

    public function bottomLeftCorner($offset = 10)
    {
        $this->args = array($offset, 'main_h-overlay_h'.$offset);
    }
}
