<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Video\Ogg;

class OggTest extends VideoTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new Ogg();
    }
}
