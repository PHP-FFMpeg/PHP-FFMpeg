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
            ->with($this->isInstanceOf('FFMpeg\Filters\Audio\ImageVideoFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));

        $format = $this->getMock('FFMpeg\Format\AudioInterface');
        $filter = new AudioFilters($audio);

        $filter->imageVideo($artwork);
        $this->assertEquals(array(0 => '-loop', 1 => 1, 2 => '-i', 3 => '/path/to/artwork.jpg', 4 => '-preset', 5 => 'veryslow', 6 => '-shortest'), $capturedFilter->apply($audio, $format));
    }
}