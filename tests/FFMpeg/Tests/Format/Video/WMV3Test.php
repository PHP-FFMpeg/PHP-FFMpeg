<?php

namespace FFMpeg\Tests\Format\Video;

use FFMpeg\Format\Video\WMV3;

class WMV3Test extends VideoTestCase
{
    public function getFormat()
    {
        return new WMV3();
    }
}
