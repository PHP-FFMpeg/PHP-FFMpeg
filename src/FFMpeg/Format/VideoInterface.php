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

interface VideoInterface extends AudioInterface
{
    /**
     * Gets the kiloBitrate value.
     *
     * @return int
     */
    public function getKiloBitrate();

    /**
     * Returns the modulus used by the Resizable video.
     *
     * This used to calculate the target dimensions while maintaining the best
     * aspect ratio.
     *
     * @see http://www.undeadborn.net/tools/rescalculator.php
     *
     * @return int
     */
    public function getModulus();

    /**
     * Returns the video codec.
     *
     * @return string
     */
    public function getVideoCodec();

    /**
     * Returns true if the current format supports B-Frames.
     *
     * @see https://wikipedia.org/wiki/Video_compression_picture_types
     *
     * @return bool
     */
    public function supportBFrames();

    /**
     * Returns the list of available video codecs for this format.
     *
     * @return array
     */
    public function getAvailableVideoCodecs();

    /**
     * Returns the list of additional parameters for this format.
     *
     * @return array
     */
    public function getAdditionalParameters();

    /**
     * Returns the list of initial parameters for this format
     *
     * @return array
     */
    public function getInitialParameters();
}
