<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Filters\Video\FrameRateFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\FrameRate;

class CustomFilterTest extends TestCase
{
    public function testApplyCustomFilter()
    {
        $video = $this->getVideoMock();
        $format = $this->getMockBuilder(\FFMpeg\Format\VideoInterface::class)->getMock();

        $filter = new CustomFilter('whatever i put would end up as a filter');
        $this->assertEquals(['-vf', 'whatever i put would end up as a filter'], $filter->apply($video, $format));
    }
}
