<?php

namespace FFMpeg\Tests\Format\Audio;

use FFMpeg\Format\Audio\Flac;

class FlacTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Flac();
    }
}
