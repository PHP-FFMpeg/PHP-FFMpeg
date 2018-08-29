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

    /**
     * @var bool
     */
    private $bframesSupport = true;

    /**
     * @var int
     */
    private $passes = 2;

    public function __construct(string $audioCodec = 'aac', string $videoCodec = 'libx264')
    {
        $this->setAudioCodec($audioCodec);
        $this->setVideoCodec($videoCodec);
    }

    /**
     * @inheritDoc
     */
    public function supportBFrames() : bool
    {
        return $this->bframesSupport;
    }

    /**
     * @param $support
     *
     * @return self
     */
    public function setBFramesSupport(bool $support) : self
    {
        $this->bframesSupport = $support;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs() : array
    {
        return ['aac', 'libvo_aacenc', 'libfaac', 'libmp3lame', 'libfdk_aac'];
    }

    /**
     * @inheritDoc
     */
    public function getAvailableVideoCodecs() : array
    {
        return ['libx264'];
    }

    /**
     * @param $passes
     *
     * @return self
     */
    public function setPasses(int $passes) : self
    {
        $this->passes = $passes;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPasses() : int
    {
        return $this->passes;
    }

    /**
     * @return int
     */
    public function getModulus() : int
    {
        return 2;
    }
}
