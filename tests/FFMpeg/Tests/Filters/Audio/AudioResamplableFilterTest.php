<?php

namespace FFMpeg\Tests\Filters\Audio;

use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Tests\TestCase;

class AudioResamplableFilterTest extends TestCase
{
    public function testGetRate()
    {
        $filter = new AudioResamplableFilter(500);
        $this->assertEquals(500, $filter->getRate());
    }

    public function testApply()
    {
        $audio = $this->getAudioMock();
        $format = $this->getMock('FFMpeg\Format\AudioInterface');

        $filter = new AudioResamplableFilter(500);
        $this->assertEquals(array('-ac', 2, '-ar', 500), $filter->apply($audio, $format));
    }
}
