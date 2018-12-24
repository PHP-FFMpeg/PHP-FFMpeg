<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\{
    DefaultAudio,
    Mp3
};

class Mp3Test extends AudioTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new Mp3();
    }
}
