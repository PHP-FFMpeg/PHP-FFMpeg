<?php

namespace FFMpeg\Tests\Format\Audio;

use FFMpeg\Format\Audio\Mp3;

class Mp3Test extends AudioTestCase
{
    public function getFormat()
    {
        return new Mp3();
    }
}
