<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\ExtractMultipleFramesFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

class ExtractMultipleFramesFilterTest extends TestCase
{
    /**
     * @dataProvider provideFrameRates
     */
    public function testApply($frameRate, $destinationFolder, $duration, $modulus, $expected)
    {
        $video = $this->getVideoMock();
        $pathfile = '/path/to/file'.mt_rand();

        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getModulus')
            ->will($this->returnValue($modulus));

        $streams = new StreamCollection(array(
            new Stream(array(
                'codec_type' => 'video',
                'duration'      => $duration,
            ))
        ));

        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));

        $filter = new ExtractMultipleFramesFilter($frameRate, $destinationFolder);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function provideFrameRates()
    {
        return array(
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, '/', 100, 2, array('-vf', 'fps=1/1', '/frame-%03d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, '/', 100, 2, array('-vf', 'fps=1/2', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, '/', 100, 2, array('-vf', 'fps=1/5', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, '/', 100, 2, array('-vf', 'fps=1/10', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, '/', 100, 2, array('-vf', 'fps=1/30', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, '/', 100, 2, array('-vf', 'fps=1/60', '/frame-%02d.jpg')),
        );
    }
}
