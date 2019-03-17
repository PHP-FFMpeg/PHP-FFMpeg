<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\Audio\AudioFilters;
use Tests\FFMpeg\Unit\TestCase;

class AudioImageVideoFilterTest extends TestCase
{
    public function testAddImageVideo(): void
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

    public function testInvalidPreset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined preset. Please pass a preset type to the method.');

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

        $filter->addVideoArtwork($artwork, 'myDumpPreset');

        $capturedFilter->apply($audio, $format);
    }
}
