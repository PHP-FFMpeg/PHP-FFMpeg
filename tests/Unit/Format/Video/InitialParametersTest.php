<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\X264;
use Tests\FFMpeg\Unit\TestCase;

class InitialParametersTest extends TestCase
{
    public function testApplyInitialParameters()
    {
        $format = new X264();
        $format->setInitialParameters(array('-acodec', 'libopus'));
        $this->assertEquals(array('-acodec', 'libopus'), $format->getInitialParameters());
    }
}
