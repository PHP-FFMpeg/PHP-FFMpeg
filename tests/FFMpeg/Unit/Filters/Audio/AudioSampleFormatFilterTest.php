<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\AudioSampleFormatFilter;
use PHPUnit\Framework\TestCase;

class AudioSampleFormatFilterTest extends TestCase
{
    public function testGetRate()
    {
        $filter = new AudioSampleFormatFilter('s16');
        $this->assertEquals('s16', $filter->getRate());
    }

    public function testApply()
    {
        $audio = $this->getAudioMock();
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        $filter = new AudioSampleFormatFilter('s16');
        $this->assertEquals(['-sample_fmt', 's16'], $filter->apply($audio, $format));
    }
}
