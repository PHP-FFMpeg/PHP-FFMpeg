<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\SynchronizeFilter;
use Tests\FFMpeg\Unit\TestCase;

class SynchronizeFilterTest extends TestCase
{
    public function testApply()
    {
        $video = $this->getVideoMock();
        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();

        $filter = new SynchronizeFilter();
        $this->assertEquals(['-async', '1', '-metadata:s:v:0', 'start_time=0'], $filter->apply($video, $format));
    }
}
