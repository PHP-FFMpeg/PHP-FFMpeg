<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Audio;

/**
 * The AAC audio format.
 */
class Aac extends DefaultAudio
{
    public function __construct()
    {
        $this->audioCodec = 'libfdk_aac';
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return ['libfdk_aac'];
    }

    /**
     * Enforce mp4 as the default container for AAC. (produces M4A files)
     * you can change it to adts or any other suitable type with setContainerFormat() method to get an aac file.
     */
    public function getFormatName(): ?string
    {
        return $this->containerFormat ?? 'mp4';
    }
}
