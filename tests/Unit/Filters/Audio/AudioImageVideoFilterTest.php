<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;


use FFMpeg\Filters\Audio\AudioFilters;
use Tests\FFMpeg\Unit\TestCase;

class AudioImageVideoFilterTest extends TestCase
{
    public function testAddImageVideo()
    {
        $artwork = '/path/to/artwork.jpg';

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf(\FFMpeg\Filters\Audio\ImageVideoFilter::class))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));

        $format = $this->getMockBuilder(\FFMpeg\Format\AudioInterface::class)->getMock();
        $filter = new AudioFilters($audio);

        $filter->addVideoArtwork($artwork, 'veryslow');
        $this->assertEquals([ '-loop', 1, '-i', '/path/to/artwork.jpg', '-preset', 'veryslow', '-shortest' ], $capturedFilter->apply($audio, $format));
    }
}
