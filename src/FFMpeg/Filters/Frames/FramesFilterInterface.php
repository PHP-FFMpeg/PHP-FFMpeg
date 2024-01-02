<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Frames;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Media\Frames;

interface FramesFilterInterface extends FilterInterface
{
    public function apply(Frames $concat);
}
