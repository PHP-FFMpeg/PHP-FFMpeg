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

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\AbstractFilter;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class RotateFilter extends AbstractFilter implements VideoFilterInterface
{
    const ROTATE_90  = '90*PI/180';
    const ROTATE_180 = 'PI';
    const ROTATE_270 = '270*PI/180';

    public function __construct($angle)
    {
        parent::__construct('rotate', array($angle));
    }
}
