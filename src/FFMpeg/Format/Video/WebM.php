<?php

namespace FFMpeg\Format\Video;

use FFMpeg\Format\DefaultVideoFormat;

class WebM extends DefaultVideoFormat
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