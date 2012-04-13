<?php

namespace FFMpeg\Format\Audio;

use FFMpeg\Format\DefaultAudioFormat;

class Flac extends DefaultAudioFormat
{

    protected $audioCodec = 'flac';

    protected function getAvailableAudioCodecs()
    {
        return array('flac');
    }

}