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
    private $pass;
    private $preset;
    
    public function __construct($audioCodec = 'libfaac', $videoCodec = 'libx264')
    {
        $this
            ->setAudioCodec($audioCodec)
            ->setVideoCodec($videoCodec);
        
        $this->pass = 2;
        $this->presets = null;
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
    public function getAvailableAudioCodecs()
    {
        return array('libvo_aacenc', 'libfaac', 'libmp3lame');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs()
    {
        return array('libx264');
    }

    /**
     * {@inheritDoc}
     */
    public function getPasses()
    {
        return $this->pass;
    }

    public function getModulus()
    {
        return 2;
    }
    
    /**
     * Force one pass
     */
    public function onlyOnePass()
    {
        $this->pass = 1;
    }
    
    /**
     * specify a preset for encoding
     * @param string $presets
     */
    public function setPreset($preset)
    {
        $this->preset = $preset;
    }
    
    public function getPreset()
    {
        return $this->preset;
    }
}
