<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\ResizeFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Coordinate\Dimension;

class ResizeFilterTest extends TestCase
{
    /**
     * @dataProvider provideDimensions
     */
    public function testApply(Dimension $dimension, $mode, $width, $height, $modulus, $expected, $forceStandards = true)
    {
        $video = $this->getVideoMock();
        $pathfile = '/path/to/file'.mt_rand();

        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('getModulus')
            ->will($this->returnValue($modulus));

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

        $filter = new ResizeFilter($dimension, $mode, $forceStandards);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function provideDimensions()
    {
        return array(
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_FIT, 640, 480, 2, array('-vf', '[in]scale=320:240 [out]')),
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_INSET, 640, 480, 2, array('-vf', '[in]scale=320:240 [out]')),
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 640, 480, 2, array('-vf', '[in]scale=320:240 [out]')),
            array(new Dimension(320, 240), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 640, 480, 2, array('-vf', '[in]scale=320:240 [out]')),

            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_FIT, 320, 240, 2, array('-vf', '[in]scale=640:480 [out]')),
            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_INSET, 320, 240, 2, array('-vf', '[in]scale=640:480 [out]')),
            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 320, 240, 2, array('-vf', '[in]scale=640:480 [out]')),
            array(new Dimension(640, 480), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 320, 240, 2, array('-vf', '[in]scale=640:480 [out]')),

            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_FIT, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_INSET, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),

            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_FIT, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_INSET, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),
            array(new Dimension(640, 360), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 1280, 720, 2, array('-vf', '[in]scale=640:360 [out]')),

            // test non standard dimension
            array(new Dimension(700, 150), ResizeFilter::RESIZEMODE_INSET, 123, 456, 2, array('-vf', '[in]scale=62:150 [out]'), true),
            array(new Dimension(700, 150), ResizeFilter::RESIZEMODE_INSET, 123, 456, 2, array('-vf', '[in]scale=40:150 [out]'), false),

            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_FIT, 640, 480, 2, array('-vf', '[in]scale=320:320 [out]')),
            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_INSET, 640, 480, 2, array('-vf', '[in]scale=320:240 [out]')),
            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, 640, 480, 2, array('-vf', '[in]scale=320:240 [out]')),
            array(new Dimension(320, 320), ResizeFilter::RESIZEMODE_SCALE_WIDTH, 640, 480, 2, array('-vf', '[in]scale=426:320 [out]')),
        );
    }
}
