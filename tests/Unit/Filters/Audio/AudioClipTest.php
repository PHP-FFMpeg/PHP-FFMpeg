<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\AudioFilters;
use FFMpeg\Coordinate\TimeCode;
use Tests\FFMpeg\Unit\TestCase;

class AudioClipTest extends TestCase {

    public function testClipping() {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf(\FFMpeg\Filters\Audio\AudioClipFilter::class))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
        }));
        $format = $this->getMockBuilder(\FFMpeg\Format\AudioInterface::class)->getMock();

        $filters = new AudioFilters($audio);

        $filters->clip(TimeCode::fromSeconds(5));
        $this->assertEquals(['-ss', '00:00:05.00', '-acodec', 'copy'], $capturedFilter->apply($audio, $format));
    }

    public function testClippingWithDuration() {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf(\FFMpeg\Filters\Audio\AudioClipFilter::class))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
        }));
        $format = $this->getMockBuilder(\FFMpeg\Format\AudioInterface::class)->getMock();

        $filters = new AudioFilters($audio);

        $filters->clip(TimeCode::fromSeconds(5), TimeCode::fromSeconds(5));
        $this->assertEquals(['-ss', '00:00:05.00', '-t', '00:00:05.00', '-acodec', 'copy'], $capturedFilter->apply($audio, $format));
    }

}