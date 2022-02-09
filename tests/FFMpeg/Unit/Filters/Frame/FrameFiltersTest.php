<?php

namespace Tests\FFMpeg\Unit\Filters\Frame;

use FFMpeg\Filters\Frame\FrameFilters;
use Tests\FFMpeg\Unit\TestCase;

class FrameFiltersTest extends TestCase
{
    public function testResize()
    {
        $frame = $this->getFrameMock();
        $filters = new FrameFilters($frame);

        $frame->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Frame\DisplayRatioFixerFilter'));

        $filters->fixDisplayRatio();
    }
}
