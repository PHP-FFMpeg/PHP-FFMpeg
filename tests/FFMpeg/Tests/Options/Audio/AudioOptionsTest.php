<?php

namespace FFMpeg\Tests\Options\Audio;

use FFMpeg\Options\Audio\AudioOptions;
use FFMpeg\Tests\TestCase;

class AudioOptionsTest extends TestCase
{
    public function testResample()
    {
        $capturedOption = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addOption')
            ->with($this->isInstanceOf('FFMpeg\Options\Audio\AudioResamplableOption'))
            ->will($this->returnCallback(function ($option) use (&$capturedOption) {
                $capturedOption = $option;
            }));

        $options = new AudioOptions($audio);
        $options->resample(8000);
        $this->assertEquals(8000, $capturedOption->getRate());
    }
}
