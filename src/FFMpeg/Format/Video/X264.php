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
 *
 * @author Romain Neutron imprec@gmail.com
 */
class X264 extends DefaultVideo
{
    protected $audioCodec = 'libmp3lame';
    protected $videoCodec = 'libx264';
    // baseline, main, high, high10, high422, high444. Online baseline and main supported by qt according to 
    // http://ffmpeg.org/trac/ffmpeg/wiki/x264EncodingGuide#compatibility
    protected $compatabilityProfile = 'baseline';

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
        return 2;
    }

    public function getModulus()
    {
        return 2;
    }
    
    public function getX264Compatibility()
    {
        return $this->compatabilityProfile;
    }
    
    public function setX264Compatibility($profile)
    {
        $this->compatabilityProfile = $profile;    
    }
    

}
