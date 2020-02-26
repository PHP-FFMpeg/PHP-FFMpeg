<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\CustomFilter;
use FFMpeg\Filters\Audio\FrameRateFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\FrameRate;

class CustomFilterTest extends TestCase
{
    public function testApplyCustomFilter()
    {
        $audio = $this->getAudioMock();
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        $filter = new CustomFilter('whatever i put would end up as a filter');
        $this->assertEquals(array('-af', 'whatever i put would end up as a filter'), $filter->apply($audio, $format));
    }
}
