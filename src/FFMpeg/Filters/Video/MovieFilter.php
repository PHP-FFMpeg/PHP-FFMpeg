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

class MovieFilter extends AbstractFilter implements VideoFilterInterface
{
    public function __construct(
        $resource,
        $format = '',
        $seekPoint = '',
        $streams = '',
        $streamIndex = '',
        $loop = ''
    )
    {
        parent::__construct('movie', array(
            $resource,
            $format,
            $seekPoint,
            $streams,
            $streamIndex,
            $loop
        ));
    }
}
