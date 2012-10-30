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

use FFMpeg\Format\VideoInterface;

/**
 * The resamplable video interface
 *
 * This interface provides frame rate and GOP size settings for video encoding
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface Resamplable extends VideoInterface
{

    /**
     * Returns the frame rate
     *
     * @return integer
     */
    public function getFrameRate();

    /**
     * Returns true if the current format supports B-Frames
     *
     * @see https://wikipedia.org/wiki/Video_compression_picture_types
     *
     * @return Boolean
     */
    public function supportBFrames();

    /**
     * Returns the GOP size
     *
     * @see https://wikipedia.org/wiki/Group_of_pictures
     *
     * @return integer
     */
    public function getGOPSize();
}
