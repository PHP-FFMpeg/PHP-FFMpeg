<?php

namespace FFMpeg\Tests\Format\Audio;

use FFMpeg\Format\Audio\Vorbis;

class VorbisTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Vorbis();
    }
}
