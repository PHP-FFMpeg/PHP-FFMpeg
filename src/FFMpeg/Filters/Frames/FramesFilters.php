<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Frames;

use FFMpeg\Media\Frames;

class FramesFilters
{
    private $frames;

    public function __construct(Frames $frames)
    {
        $this->frames = $frames;
    }
}
