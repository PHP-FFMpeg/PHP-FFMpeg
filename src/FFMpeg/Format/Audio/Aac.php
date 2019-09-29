<?php

declare(strict_types=1);

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
 * The AAC audio format
 */
class Aac extends DefaultAudio
{
    public function __construct()
    {
        $this->audioCodec = 'aac';
    }

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs()
    {
        return ['aac', 'libfdk_aac'];
    }
}
