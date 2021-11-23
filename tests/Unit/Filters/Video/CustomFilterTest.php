<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\CustomFilter;
use Tests\FFMpeg\Unit\TestCase;

class CustomFilterTest extends TestCase
{
    public function testApplyCustomFilter()
    {
        $video = $this->getVideoMock();
        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();

        $filter = new CustomFilter('whatever i put would end up as a filter');
        $this->assertEquals(['-vf', 'whatever i put would end up as a filter'], $filter->apply($video, $format));
    }
}
