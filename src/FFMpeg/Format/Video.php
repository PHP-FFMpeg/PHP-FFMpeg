<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format;

/**
 * The video format interface
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface Video extends Audio
{

    /**
     * Returns the width
     *
     * @return integer
     */
    public function getWidth();

    /**
     * Returns the height
     *
     * @return integer
     */
    public function getHeight();

    /**
     * Returns the frame rate
     *
     * @return integer
     */
    public function getFrameRate();

    /**
     * Returns the video codec
     *
     * @return string
     */
    public function getVideoCodec();

    /**
     * Returns the GOP size
     *
     * @return integer
     */
    public function getGOPSize();

    /**
     * Returns the list of available video codecs for this format
     *
     * @return array
     */
    public function getAvailableVideoCodecs();
}
