<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Akuma <meru.no.akuma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

/**
 * The FLV video format
 */
class FLV extends DefaultVideo
{
    public function __construct($audioCodec = 'libmp3lame', $videoCodec = 'flv')
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
        return array('-ar', '22050', '-g', '25');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('libmp3lame');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs()
    {
        return array('flv');
    }
}
