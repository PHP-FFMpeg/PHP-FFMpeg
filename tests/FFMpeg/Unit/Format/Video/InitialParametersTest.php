<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\X264;
use Tests\FFMpeg\Unit\TestCase;

class InitialParametersTest extends TestCase
{
    public function testApplyInitialParameters()
    {
        $format = new X264();
        $format->setInitialParameters(['-acodec', 'libopus']);
        $this->assertEquals(['-acodec', 'libopus'], $format->getInitialParameters());
    }
}
