<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Video\WMV;

class WMVTest extends VideoTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new WMV();
    }
}
