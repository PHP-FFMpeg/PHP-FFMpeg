<?php

namespace FFMpeg\Format\Audio;

use FFMpeg\Format\DefaultAudioFormat;

class Mp3 extends DefaultAudioFormat
{

    protected $audioCodec = 'libmp3lame';

    protected function getAvailableAudioCodecs()
    {
        return array('libmp3lame');
    }

}