<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

use FFMpeg\Format\Video as BaseVideo;
use FFMpeg\Format\Dimension;

/**
 * The resizable video interface
 *
 * This interface provides methods for video resizing.
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface Resizable extends BaseVideo
{

    /**
     * Returns the computed dimensions for the resize, after operation.
     * This method return the actual dimensions that FFmpeg will use.
     *
     * @param  integer   $originalWidth
     * @param  integer   $originalHeight
     * @return Dimension A dimension
     */
    public function getComputedDimensions($originalWidth, $originalHeight);
}
