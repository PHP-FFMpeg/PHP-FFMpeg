<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\Tests\TestCase;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\Filters\Video\SynchronizeFilter;

class SynchronizeFilterTest extends TestCase
{
    /**
     * @dataProvider provideStreams
     */
    public function testApply($streams, $expected)
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));
        $video->expects($this->any())
            ->method('getPathfile')
            ->will($this->returnValue(__FILE__));

        $filter = new SynchronizeFilter();
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function provideStreams()
    {
        $audio = new StreamCollection(array(new Stream(array(
            'index' => 0,
            'codec_type' => 'audio',
        ))));
        $synced = new StreamCollection(array(new Stream(array(
            'index' => 0,
            'codec_type' => 'video',
        )), new Stream(array(
            'index' => 1,
            'codec_type' => 'audio',
        ))));
        $video = new StreamCollection(array(new Stream(array(
            'index' => 0,
            'codec_type' => 'video',
            'start_time' => '0.123456',
        )), new Stream(array(
            'index' => 1,
            'codec_type' => 'audio',
        ))));

        return array(
            array($audio, array()),
            array($synced, array()),
            array($video, array('-itsoffset', '0.123456', '-i', __FILE__, '-map', '1:0', '-map', '0:1')),
        );
    }
}
