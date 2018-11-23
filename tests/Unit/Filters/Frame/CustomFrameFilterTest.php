<?php

namespace Tests\FFMpeg\Unit\Filters\Frame;

use FFMpeg\Filters\Frame\CustomFrameFilter;
use Tests\FFMpeg\Unit\TestCase;

class CustomFrameFilterTest extends TestCase
{
    public function testApplyCustomFrameFilter()
    {
        $frame = $this->getFrameMock();

        $filter = new CustomFrameFilter('whatever i put would end up as a filter');
        $this->assertEquals(array('-vf', 'whatever i put would end up as a filter'), $filter->apply($frame));
    }
}
