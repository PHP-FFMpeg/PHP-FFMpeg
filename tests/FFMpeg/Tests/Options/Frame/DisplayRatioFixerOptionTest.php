<?php

namespace FFMpeg\Tests\Options\Frame;

use FFMpeg\Tests\TestCase;
use FFMpeg\Options\Frame\DisplayRatioFixerOption;
use FFMpeg\Media\Frame;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\FFProbe\DataMapping\Stream;

class DisplayRatioFixerOptionTest extends TestCase
{
    public function testApply()
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960, 'height' => 720));
        $streams = new StreamCollection(array($stream));

        $video = $this->getVideoMock(__FILE__);
        $video->expects($this->once())
                ->method('getStreams')
                ->will($this->returnValue($streams));

        $frame = new Frame($video, $this->getFFMpegDriverMock(), $this->getFFProbeMock(), new TimeCode(0, 0, 0, 0));
        $option = new DisplayRatioFixerOption();
        $this->assertEquals(array('-s', '960x720'), $option->apply($frame));
    }
}
