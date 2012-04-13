<?php

namespace FFMpeg\Format;

interface AudioFormat
{

    public function getAudioCodec();

    public function getAudioSampleRate();

    public function getKiloBitrate();

}