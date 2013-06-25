<?php

namespace FFMpeg\Tests\Format\Video;

use FFMpeg\Format\Video\WMV;

class WMVTest extends VideoTestCase
{
    public function getFormat()
    {
        return new WMV();
    }
}
