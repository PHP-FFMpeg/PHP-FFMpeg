<?php

namespace FFMpeg\Format\Audio;
use FFMpeg\Format\Audio\DefaultAudio;

class Amr extends  DefaultAudio
{
    public function __construct()
    {
        $this->audioCodec = 'amr_nb';
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('amr_nb');
    }

    public function getAudioKiloBitrate()
    {
        return '12.2';
    }

    public function getExtraParams()
    {
        return ['-ar', 8000];
    }
}
