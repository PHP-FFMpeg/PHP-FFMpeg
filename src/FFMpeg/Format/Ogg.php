<?php

namespace FFMpeg\Format;

class Ogg extends DefaultFormat
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