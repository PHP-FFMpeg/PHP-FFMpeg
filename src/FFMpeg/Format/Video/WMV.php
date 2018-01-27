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
 * The WMV video format
 */
class WMV extends DefaultVideo
{

    /**
     * @inheritDoc
     */
    protected $audioCodec = 'wmav2';

    /**
     * @inheritDoc
     */
    protected $videoCodec = 'wmv2';

    /**
     * @deprecated 1.0.0 use setters directly
     */
    public function __construct(string $audioCodec = 'wmav2', string $videoCodec = 'wmv2')
    {
        $this->setAudioCodec($audioCodec);
        $this->setVideoCodec($videoCodec);
    }

    /**
     * @inheritDoc
     */
    public function supportBFrames(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs(): array
    {
        return ['wmav2'];
    }

    /**
     * @inheritDoc
     */
    public function getAvailableVideoCodecs(): array
    {
        return ['wmv2'];
    }
}
