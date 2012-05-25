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

interface Audio
{

    /**
     * Returns the audio codec
     *
     * @return string
     */
    public function getAudioCodec();

    /**
     * Get the audio sample rate
     *
     * @return integer
     */
    public function getAudioSampleRate();

    /**
     * Get the kiloBitrate value
     *
     * @return integer
     */
    public function getKiloBitrate();

    /**
     * Returns the list of available audio codecs for this format
     *
     * @return array
     */
    public function getAvailableAudioCodecs();
}
