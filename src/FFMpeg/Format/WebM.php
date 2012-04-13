<?php

namespace FFMpeg\Format;

class WebM extends DefaultFormat
{

    protected $audioCodec = 'libvorbis';
    protected $videoCodec = 'libvpx';

    public function getExtraParams()
    {
        return '-f webm';
    }

    protected function getAvailableAudioCodecs()
    {
        return array('libvorbis');
    }

    protected function getAvailableVideoCodecs()
    {
        return array('libvpx');
    }

}