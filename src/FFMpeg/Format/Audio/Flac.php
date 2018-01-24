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
 * The Flac audio format
 */
class Flac extends DefaultAudio
{

    /**
     * @inheritDoc
     */
    protected $audioCodec = 'flac';

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs(): array
    {
        return ['flac'];
    }
}
