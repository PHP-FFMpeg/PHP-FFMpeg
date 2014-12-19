<?php

namespace FFMpeg\Tests\Options\Video;

use FFMpeg\Options\Video\FrameRateOption;
use FFMpeg\Tests\TestCase;
use FFMpeg\Coordinate\FrameRate;

class FrameRateOptionTest extends TestCase
{
    public function testApplyWithAFormatThatSupportsBFrames()
    {
        $framerate = new FrameRate(54);
        $gop = 42;

        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('supportBFrames')
            ->will($this->returnValue(true));

        $expected = array('-r', 54, '-b_strategy', '1', '-bf', '3', '-g', 42);

        $option = new FrameRateOption($framerate, $gop);
        $this->assertEquals($expected, $option->apply($video, $format));
    }

    public function testApplyWithAFormatThatDoesNotSupportsBFrames()
    {
        $framerate = new FrameRate(54);
        $gop = 42;

        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('supportBFrames')
            ->will($this->returnValue(false));

        $expected = array('-r', 54);

        $option = new FrameRateOption($framerate, $gop);
        $this->assertEquals($expected, $option->apply($video, $format));
    }
}
