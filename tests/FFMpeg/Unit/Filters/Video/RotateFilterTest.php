<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Filters\Video\RotateFilter;
use Tests\FFMpeg\Unit\TestCase;

class RotateFilterTest extends TestCase
{
    /**
     * @dataProvider provide90degresTranspositions
     */
    public function testApplyWithSizeTransformation($value)
    {
        $stream = new Stream(['width' => 320, 'height' => 240, 'codec_type' => 'video']);
        $streams = new StreamCollection([$stream]);

        $video = $this->getVideoMock();
        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));

        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();

        $filter = new RotateFilter($value);
        $this->assertEquals(['-vf', $value, '-metadata:s:v:0', 'rotate=0'], $filter->apply($video, $format));

        $this->assertEquals(240, $stream->get('width'));
        $this->assertEquals(320, $stream->get('height'));
    }

    public function provide90degresTranspositions()
    {
        return [
            [RotateFilter::ROTATE_90],
            [RotateFilter::ROTATE_270],
        ];
    }

    /**
     * @dataProvider provideDegresWithoutTranspositions
     */
    public function testApplyWithoutSizeTransformation($value)
    {
        $video = $this->getVideoMock();
        $video->expects($this->never())
            ->method('getStreams');

        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();

        $filter = new RotateFilter($value);
        $this->assertEquals(['-vf', $value, '-metadata:s:v:0', 'rotate=0'], $filter->apply($video, $format));
    }

    public function provideDegresWithoutTranspositions()
    {
        return [
            [RotateFilter::ROTATE_180],
        ];
    }

    public function testApplyInvalidAngle()
    {
        $this->expectException(
            '\FFMpeg\Exception\InvalidArgumentException',
            'Invalid angle value.'
        );
        new RotateFilter('90');
    }
}
