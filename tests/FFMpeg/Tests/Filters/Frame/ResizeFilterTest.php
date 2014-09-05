<?php

namespace FFMpeg\Tests\Filters\Frame;

use FFMpeg\Filters\Frame\ResizeFilter;
use FFMpeg\Tests\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Coordinate\Dimension;

class ResizeFilterTest extends TestCase
{
    /**
     * @dataProvider provideDimensions
     */
    public function testApply(Dimension $dimension, $mode, $width, $height, $expected, $forceStandards = true)
    {
        $video = $this->getVideoMock();
        $frame = $this->getFrameMock();
        $pathfile = '/path/to/file'.mt_rand();

        $streams = new StreamCollection(array(
            new Stream(array(
                'codec_type' => 'video',
                'width'      => $width,
                'height'     => $height,
            ))
        ));

        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));

        $frame->expects($this->once())
            ->method('getVideo')
            ->will($this->returnValue($video));

        $filter = new ResizeFilter($dimension, $mode, $forceStandards);
        $this->assertEquals($expected, $filter->apply($frame));
    }

    public function provideDimensions()
    {
        return array(
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_FIT, 640, 480, array('-s', '320x240')),
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_INSET, 640, 480, array('-s', '320x240')),
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 640, 480, array('-s', '320x240')),
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 640, 480, array('-s', '320x240')),

            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_FIT, 320, 240, array('-s', '640x480')),
            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_INSET, 320, 240, array('-s', '640x480')),
            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 320, 240, array('-s', '640x480')),
            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 320, 240, array('-s', '640x480')),

            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_FIT, 1280, 720, array('-s', '640x360')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_INSET, 1280, 720, array('-s', '640x360')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 1280, 720, array('-s', '640x360')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 1280, 720, array('-s', '640x360')),

            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_FIT, 1280, 720, array('-s', '640x360')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_INSET, 1280, 720, array('-s', '640x360')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 1280, 720, array('-s', '640x360')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 1280, 720, array('-s', '640x360')),

            // test non standard dimension
            array(new Dimension(700, 150), ResizeFilter::RESIZEMODE_INSET, 123, 456, array('-s', '63x150'), true),
            array(new Dimension(700, 150), ResizeFilter::RESIZEMODE_INSET, 123, 456, array('-s', '40x150'), false),

            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_FIT, 640, 480, array('-s', '320x320')),
            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_INSET, 640, 480, array('-s', '320x240')),
            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 640, 480, array('-s', '320x240')),
            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 640, 480, array('-s', '427x320')),
        );
    }
}
