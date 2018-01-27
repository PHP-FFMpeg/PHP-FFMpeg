<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Concat;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Media\Concat;

interface ConcatFilterInterface extends FilterInterface
{
    public function apply(Concat $concat);
}
