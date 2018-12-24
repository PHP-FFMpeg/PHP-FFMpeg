<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\{
    DefaultAudio,
    Flac
};

class FlacTest extends AudioTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new Flac();
    }
}
