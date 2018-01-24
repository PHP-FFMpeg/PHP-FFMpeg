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
 * The Vorbis audio format
 */
class Vorbis extends DefaultAudio
{

    /**
     * @inheritDoc
     */
    protected $audioCodec = 'vorbis';

    /**
     * @inheritDoc
     */
    public function getExtraParams(): array
    {
        return ['-strict', '-2'];
    }

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs(): array
    {
        return ['vorbis'];
    }
}
