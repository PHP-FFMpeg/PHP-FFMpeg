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
 * The Vorbis audio format.
 */
class Vorbis extends DefaultAudio
{
    public function __construct()
    {
        $this->audioCodec = 'vorbis';
    }

    /**
     * {@inheritdoc}
     */
    public function getExtraParams(): array
    {
        $params   = parent::getExtraParams();
        $params[] = '-strict';
        $params[] = '-2';
        return $params;
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return ['vorbis'];
    }

    /**
     * Enforce ogg as the default container for Vorbis.
     * you can change it to oga or any other suitable type with setContainerFormat() method.
     */
    public function getFormatName(): ?string
    {
        return 'ogg';
    }
}
