<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\Tests\TestCase;
use FFMpeg\Filters\Video\SynchronizeFilter;

class SynchronizeFilterTest extends TestCase
{
    public function testApply()
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $filter = new SynchronizeFilter();
        $this->assertEquals(array('-async', '1', '-metadata:s:v:0', 'start_time=0'), $filter->apply($video, $format));
    }
}
