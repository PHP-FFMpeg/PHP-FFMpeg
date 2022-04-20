<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Filters\Video\FrameRateFilter;
use Tests\FFMpeg\Unit\TestCase;

class FrameRateFilterTest extends TestCase
{
    public function testApplyWithAFormatThatSupportsBFrames()
    {
        $framerate = new FrameRate(54);
        $gop = 42;

        $video = $this->getVideoMock();
        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('supportBFrames')
            ->will($this->returnValue(true));

        $expected = ['-r', 54, '-b_strategy', '1', '-bf', '3', '-g', 42];

        $filter = new FrameRateFilter($framerate, $gop);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function testApplyWithAFormatThatDoesNotSupportsBFrames()
    {
        $framerate = new FrameRate(54);
        $gop = 42;

        $video = $this->getVideoMock();
        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('supportBFrames')
            ->will($this->returnValue(false));

        $expected = ['-r', 54];

        $filter = new FrameRateFilter($framerate, $gop);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }
}
