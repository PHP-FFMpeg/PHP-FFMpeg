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

interface AudioInterface extends FormatInterface
{
    /**
     * Gets the audio kiloBitrate value.
     *
     * @return int
     */
    public function getAudioKiloBitrate();

    /**
     * Gets the audio channels value.
     *
     * @return int
     */
    public function getAudioChannels();

    /**
     * Returns the audio codec.
     *
     * @return string
     */
    public function getAudioCodec();

    /**
     * Returns the list of available audio codecs for this format.
     *
     * @return array
     */
    public function getAvailableAudioCodecs();
}
