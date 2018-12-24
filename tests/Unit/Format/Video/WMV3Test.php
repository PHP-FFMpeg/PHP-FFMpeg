<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Video\WMV3;

class WMV3Test extends VideoTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new WMV3();
    }
}
