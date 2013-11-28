<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\Filters\Video\RotateFilter;
use FFMpeg\Tests\TestCase;

class RotateFilterTest extends TestCase
{
    public function testApply()
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $filter = new RotateFilter(RotateFilter::ROTATE_90);
        $this->assertEquals(array('-vf', RotateFilter::ROTATE_90, '-metadata:s:v:0', 'rotate=0'), $filter->apply($video, $format));
    }

    /**
     * @expectedException \FFMpeg\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid angle value.
     */
    public function testApplyInvalidAngle()
    {
        new RotateFilter('90');
    }
}
