<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\AudioFilters;
use FFMpeg\Coordinate\TimeCode as TimeCode;
use Tests\FFMpeg\Unit\TestCase;

class AudioClipFilterTest extends TestCase
{
    public function testClipAudio()
    {
        $ffprobe = $this->getFFProbeMock();
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AudioClipFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMock('FFMpeg\Format\AudioInterface');

        $filters = new AudioFilters($audio);
        $filters->clip(TimeCode::fromSeconds(0), TimeCode::fromSeconds(20));

        // how to check length of clipped audio?
       
    }


}
