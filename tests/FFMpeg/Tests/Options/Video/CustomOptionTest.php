<?php

namespace FFMpeg\Tests\Options\Video;

use FFMpeg\Options\Video\CustomOption;
use FFMpeg\Options\Video\FrameRateOption;
use FFMpeg\Tests\TestCase;
use FFMpeg\Coordinate\FrameRate;

class CustomOptionTest extends TestCase
{
    public function testApplyCustomOption()
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $option = new CustomOption('-option', 'whatever i put would end up as a option');
        $this->assertEquals(array('-option', 'whatever i put would end up as a option'), $option->apply($video, $format));
    }
}
