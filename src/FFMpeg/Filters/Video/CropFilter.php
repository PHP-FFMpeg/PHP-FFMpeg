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

class CropFilter extends AbstractFilter implements VideoFilterInterface
{
    public function __construct($w, $h, $x, $y, $keepAspect = 0)
    {
        parent::__construct('crop', array($w, $h, $x, $y, $keepAspect));
    }
}
