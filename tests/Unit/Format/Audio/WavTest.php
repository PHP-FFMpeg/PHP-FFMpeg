<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\{
    DefaultAudio,
    Wav
};

class WavTest extends AudioTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new Wav();
    }
}
