<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Video\WebM;

class WebMTest extends VideoTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new WebM();
    }
}
