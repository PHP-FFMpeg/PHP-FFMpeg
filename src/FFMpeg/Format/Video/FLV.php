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
class FLV extends DefaultVideo
{
    public function __construct($audioCodec = 'mp3', $videoCodec = 'libx264')
    {
        $this
            ->setAudioCodec($audioCodec)
            ->setVideoCodec($videoCodec);
    }

    /**
     * {@inheritDoc}
     */
    public function supportBFrames()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraParams()
    {
        return array('-f', 'flv');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('copy', 'mp3');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs()
    {
        return array('libx264');
    }
}
