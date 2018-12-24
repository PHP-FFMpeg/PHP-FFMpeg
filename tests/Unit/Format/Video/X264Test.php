<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Video\X264;

class X264Test extends VideoTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new X264();
    }
}
