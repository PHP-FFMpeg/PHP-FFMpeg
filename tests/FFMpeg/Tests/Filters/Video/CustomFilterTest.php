<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Filters\Video\FrameRateFilter;
use FFMpeg\Tests\TestCase;
use FFMpeg\Coordinate\FrameRate;

class CustomFilterTest extends TestCase
{
    public function testApplyCustomFilter()
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $filter = new CustomFilter('whatever i put would end up as a filter');
        $this->assertEquals(array('-vf', 'whatever i put would end up as a filter'), $filter->apply($video, $format));
    }
}
