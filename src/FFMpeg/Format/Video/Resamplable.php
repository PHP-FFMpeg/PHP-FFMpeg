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
 * The resamplable video interface
 *
 * This interface provides frame rate and GOP size settings for video encoding
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface Resamplable extends BaseVideo
{

    /**
     * Returns the frame rate
     *
     * @return integer
     */
    public function getFrameRate();

    /**
     * Returns the GOP size
     *
     * @return integer
     */
    public function getGOPSize();
}
