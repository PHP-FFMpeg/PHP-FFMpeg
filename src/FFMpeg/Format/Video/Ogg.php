<?php

namespace FFMpeg\Format\Video;

use FFMpeg\Format\DefaultVideoFormat;

class Ogg extends DefaultVideoFormat
{

    protected $audioCodec = 'libvorbis';
    protected $videoCodec = 'libtheora';

    protected function getAvailableAudioCodecs()
    {
        return array('libvorbis');
    }

    protected function getAvailableVideoCodecs()
    {
        return array('libtheora');
    }

}