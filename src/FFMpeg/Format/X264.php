<?php

namespace FFMpeg\Format;

class X264 extends DefaultFormat
{

    protected $audioCodec = 'libmp3lame';
    protected $videoCodec = 'libx264';

    protected function getAvailableAudioCodecs()
    {
        return array('libvo_aacenc', 'libfaac', 'libmp3lame');
    }

    protected function getAvailableVideoCodecs()
    {
        return array('libx264');
    }

}