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

/**
 * The resizable video interface
 * 
 * This interface provides methods for video resizing.
 * 
 * @author Romain Neutron imprec@gmail.com
 */
interface ResizableVideo extends BaseVideo
{

    /**
     * Returns the width setting. 
     * The return of this method should not depend on a media file size
     *
     * @return integer
     */
    public function getWidth();

    /**
     * Returns the height setting
     * The return of this method should not depend on a media file size
     *
     * @return integer
     */
    public function getHeight();

    /**
     * Returns the computed dimensions for the resize, after operation.
     * This method return the actual dimensions that FFmpeg will use.
     *
     * @param  integer $originalWidth
     * @param  integer $originalHeight
     * @return array   An indexed array containing the width and the height
     */
    public function getComputedDimensions($originalWidth, $originalHeight);

    /**
     * Get the current resize mode name
     *
     * @return string
     */
    public function getResizeMode();
}
