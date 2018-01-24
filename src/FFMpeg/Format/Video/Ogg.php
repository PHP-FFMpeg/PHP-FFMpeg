<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

/**
 * The Ogg video format
 */
class Ogg extends DefaultVideo
{

    /**
     * @inheritDoc
     */
    protected $audioCodec = 'libvorbis';

    /**
     * @inheritDoc
     */
    protected $videoCodec = 'libtheora';

    /**
     * @deprecated 1.0.0 use setters directly
     */
    public function __construct(string $audioCodec = 'libvorbis', string $videoCodec = 'libtheora')
    {
        $this->setAudioCodec($audioCodec);
        $this->setVideoCodec($videoCodec);
    }

    /**
     * @inheritDoc
     */
    public function supportBFrames(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs(): array
    {
        return ['libvorbis'];
    }

    /**
     * @inheritDoc
     */
    public function getAvailableVideoCodecs(): array
    {
        return ['libtheora'];
    }
}
