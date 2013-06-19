<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\Tests\TestCase;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Filters\Video\ResizeFilter;

class VideoFiltersTest extends TestCase
{
    /**
     * @dataProvider provideResizeOptions
     */
    public function testResize($mode, $forceStandards)
    {
        $capturedFilter = null;

        $ffprobe = $this->getFFProbeMock();
        $video = $this->getVideoMock();
        $video->expects($this->any())
            ->method('getFFProbe')
            ->will($this->returnValue($ffprobe));
        $filters = new VideoFilters($video);
        $dimension = $this->getDimensionMock();

        $video->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Video\ResizeFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));

        $filters->resize($dimension, $mode, $forceStandards);

        $this->assertSame($ffprobe, $capturedFilter->getFFProbe());
        $this->assertSame($mode, $capturedFilter->getMode());
        $this->assertSame($forceStandards, $capturedFilter->areStandardsForced());
        $this->assertSame($dimension, $capturedFilter->getDimension());
    }

    public function provideResizeOptions()
    {
        return array(
            array(ResizeFilter::RESIZEMODE_FIT, true),
            array(ResizeFilter::RESIZEMODE_SCALE_WIDTH, true),
            array(ResizeFilter::RESIZEMODE_SCALE_HEIGHT, false),
            array(ResizeFilter::RESIZEMODE_INSET, false),
        );
    }

    public function testResample()
    {
        $capturedFilter = null;

        $video = $this->getVideoMock();
        $filters = new VideoFilters($video);
        $framerate = $this->getFramerateMock();
        $gop = 42;

        $video->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Video\VideoResampleFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));

        $filters->resample($framerate, $gop);

        $this->assertSame($framerate, $capturedFilter->getFramerate());
        $this->assertSame($gop, $capturedFilter->getGOP());
    }
}