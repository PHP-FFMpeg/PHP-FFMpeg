<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Gif;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Media\Gif;

interface GifFilterInterface extends FilterInterface
{
    public function apply(Gif $gif);
}
