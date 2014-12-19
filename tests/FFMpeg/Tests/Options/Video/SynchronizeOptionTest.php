<?php

namespace FFMpeg\Tests\Options\Video;

use FFMpeg\Tests\TestCase;
use FFMpeg\Options\Video\SynchronizeOption;

class SynchronizeOptionTest extends TestCase
{
    public function testApply()
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $option = new SynchronizeOption();
        $this->assertEquals(array('-async', '1', '-metadata:s:v:0', 'start_time=0'), $option->apply($video, $format));
    }
}
