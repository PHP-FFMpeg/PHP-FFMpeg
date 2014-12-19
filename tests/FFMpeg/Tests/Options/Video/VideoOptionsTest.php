<?php

namespace FFMpeg\Tests\Options\Video;

use FFMpeg\Tests\TestCase;
use FFMpeg\Options\Video\VideoOptions;
use FFMpeg\Options\Video\ResizeOption;

class VideoOptionsTest extends TestCase
{
    /**
     * @dataProvider provideResizeOptions
     */
    public function testResize($mode, $forceStandards)
    {
        $capturedOption = null;

        $video = $this->getVideoMock();
        $options = new VideoOptions($video);
        $dimension = $this->getDimensionMock();

        $video->expects($this->once())
            ->method('addOption')
            ->with($this->isInstanceOf('FFMpeg\Options\Video\ResizeOption'))
            ->will($this->returnCallback(function ($option) use (&$capturedOption) {
                $capturedOption = $option;
            }));

        $options->resize($dimension, $mode, $forceStandards);

        $this->assertSame($mode, $capturedOption->getMode());
        $this->assertSame($forceStandards, $capturedOption->areStandardsForced());
        $this->assertSame($dimension, $capturedOption->getDimension());
    }

    public function provideResizeOptions()
    {
        return array(
            array(ResizeOption::RESIZEMODE_FIT, true),
            array(ResizeOption::RESIZEMODE_SCALE_WIDTH, true),
            array(ResizeOption::RESIZEMODE_SCALE_HEIGHT, false),
            array(ResizeOption::RESIZEMODE_INSET, false),
        );
    }

    public function testResample()
    {
        $capturedOption = null;

        $video = $this->getVideoMock();
        $options = new VideoOptions($video);
        $framerate = $this->getFramerateMock();
        $gop = 42;

        $video->expects($this->once())
            ->method('addOption')
            ->with($this->isInstanceOf('FFMpeg\Options\Video\FrameRateOption'))
            ->will($this->returnCallback(function ($option) use (&$capturedOption) {
                $capturedOption = $option;
            }));

        $options->framerate($framerate, $gop);

        $this->assertSame($framerate, $capturedOption->getFramerate());
        $this->assertSame($gop, $capturedOption->getGOP());
    }

    public function testSynchronize()
    {
        $video = $this->getVideoMock();
        $options = new VideoOptions($video);

        $video->expects($this->once())
            ->method('addOption')
            ->with($this->isInstanceOf('FFMpeg\Options\Video\SynchronizeOption'));

        $options->synchronize();
    }
}
