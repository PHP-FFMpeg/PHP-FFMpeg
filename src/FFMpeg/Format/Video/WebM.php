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
 * The WebM video format
 */
class WebM extends DefaultVideo
{

    /**
     * @inheritDoc
     */
    protected $audioCodec = 'libvorbis';

    /**
     * @inheritDoc
     */
    protected $videoCodec = 'libvpx';

    /**
     * @deprecated 1.0.0 use setters directly
     */
    public function __construct(string $audioCodec = 'libvorbis', string $videoCodec = 'libvpx')
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
    public function getExtraParams(): array
    {
        return ['-f', 'webm'];
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
        return ['libvpx', 'libvpx-vp9'];
    }
}
