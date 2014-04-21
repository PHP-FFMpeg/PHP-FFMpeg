<?php

namespace FFMpeg\Tests\Format\Audio;

use FFMpeg\Format\Audio\Wav;

class WavTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Wav();
    }
}
