<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\{
    DefaultAudio,
    Vorbis
};

class VorbisTest extends AudioTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new Vorbis();
    }
}
