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
    public function testApply($frameRate, $frameFileType,$destinationFolder, $duration, $modulus, $expected)
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
                'duration'      => $duration,
            ))
        ));

        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));

        $filter = new ExtractMultipleFramesFilter($frameRate, $destinationFolder);
        $filter->setFrameFileType($frameFileType);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function provideFrameRates()
    {
        return array(
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, 'jpg', '/', 100, 2, array('-vf', 'fps=1/1', '/frame-%03d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, 'jpg', '/', 100, 2, array('-vf', 'fps=1/2', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, 'jpg', '/', 100, 2, array('-vf', 'fps=1/5', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, 'jpg', '/', 100, 2, array('-vf', 'fps=1/10', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, 'jpg', '/', 100, 2, array('-vf', 'fps=1/30', '/frame-%02d.jpg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, 'jpg', '/', 100, 2, array('-vf', 'fps=1/60', '/frame-%02d.jpg')),

            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, 'jpeg', '/', 100, 2, array('-vf', 'fps=1/1', '/frame-%03d.jpeg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, 'jpeg', '/', 100, 2, array('-vf', 'fps=1/2', '/frame-%02d.jpeg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, 'jpeg', '/', 100, 2, array('-vf', 'fps=1/5', '/frame-%02d.jpeg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, 'jpeg', '/', 100, 2, array('-vf', 'fps=1/10', '/frame-%02d.jpeg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, 'jpeg', '/', 100, 2, array('-vf', 'fps=1/30', '/frame-%02d.jpeg')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, 'jpeg', '/', 100, 2, array('-vf', 'fps=1/60', '/frame-%02d.jpeg')),

            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, 'png', '/', 100, 2, array('-vf', 'fps=1/1', '/frame-%03d.png')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, 'png', '/', 100, 2, array('-vf', 'fps=1/2', '/frame-%02d.png')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, 'png', '/', 100, 2, array('-vf', 'fps=1/5', '/frame-%02d.png')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, 'png', '/', 100, 2, array('-vf', 'fps=1/10', '/frame-%02d.png')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, 'png', '/', 100, 2, array('-vf', 'fps=1/30', '/frame-%02d.png')),
            array(ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, 'png', '/', 100, 2, array('-vf', 'fps=1/60', '/frame-%02d.png')),
        );
    }

    public function testInvalidFrameFileType() {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $filter = new ExtractMultipleFramesFilter('1/1', '/');
        $filter->setFrameFileType('webm');
    }
}
