<?php

namespace FFMpeg\Tests\Options\Audio;

use FFMpeg\Options\Audio\AudioResamplableOption;
use FFMpeg\Tests\TestCase;

class AudioResamplableOptionTest extends TestCase
{
    public function testGetRate()
    {
        $option = new AudioResamplableOption(500);
        $this->assertEquals(500, $option->getRate());
    }

    public function testApply()
    {
        $audio = $this->getAudioMock();
        $format = $this->getMock('FFMpeg\Format\AudioInterface');

        $option = new AudioResamplableOption(500);
        $this->assertEquals(array('-ac', 2, '-ar', 500), $option->apply($audio, $format));
    }
}
