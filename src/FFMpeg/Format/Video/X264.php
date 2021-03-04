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
 * The X264 video format
 */
class X264 extends DefaultVideo
{
    /** @var bool */
    private $bframesSupport = true;

    /** @var int */
    private $passes = 2;

    public function __construct($audioCodec = 'aac', $videoCodec = 'libx264')
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
        return $this->bframesSupport;
    }

    /**
     * @param $support
     *
     * @return X264
     */
    public function setBFramesSupport($support)
    {
        $this->bframesSupport = $support;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('copy', 'aac', 'libvo_aacenc', 'libfaac', 'libmp3lame', 'libfdk_aac');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs()
    {
        return array('libx264');
    }

    /**
     * @param $passes
     *
     * @return X264
     */
    public function setPasses($passes)
    {
        $this->passes = $passes;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPasses()
    {
        return $this->getKiloBitrate() === 0 ? 1 : $this->passes;
    }

    /**
     * @return int
     */
    public function getModulus()
    {
        return 2;
    }
}
