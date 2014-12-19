<?php

namespace FFMpeg\Tests\Options\Frame;

use FFMpeg\Tests\TestCase;
use FFMpeg\Options\Frame\FrameOptions;

class FrameOptionsTest extends TestCase
{
    public function testResize()
    {
        $frame = $this->getFrameMock();
        $options = new FrameOptions($frame);

        $frame->expects($this->once())
            ->method('addOption')
            ->with($this->isInstanceOf('FFMpeg\Options\Frame\DisplayRatioFixerOption'));

        $options->fixDisplayRatio();
    }
}
