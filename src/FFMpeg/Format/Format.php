<?php

namespace FFMpeg\Format;

interface Format
{

    public function getExtraParams();

    public function getWidth();

    public function getHeight();

    public function getFrameRate();

    public function getAudioCodec();

    public function getAudioSampleRate();

    public function getVideoCodec();

    public function getKiloBitrate();

    public function getGOPSize();

}