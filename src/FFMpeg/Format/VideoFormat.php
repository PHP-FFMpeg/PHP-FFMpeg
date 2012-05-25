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

interface VideoFormat extends AudioFormat
{

    public function getWidth();

    public function getHeight();

    public function getFrameRate();

    public function getVideoCodec();

    public function getGOPSize();

    /**
     * Returns the list of available video codecs for this format
     *
     * @return array
     */
    public function getAvailableVideoCodecs();
}
