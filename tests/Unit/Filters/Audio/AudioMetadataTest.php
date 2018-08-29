<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\AudioFilters;
use Tests\FFMpeg\Unit\TestCase;

class AudioMetadataTest extends TestCase
{
    public function testAddMetadata()
    {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf(\FFMpeg\Filters\Audio\AddMetadataFilter::class))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMockBuilder(\FFMpeg\Format\AudioInterface::class)->getMock();

        $filters = new AudioFilters($audio);
        $filters->addMetadata(['title' => "Hello World"]);
        $this->assertEquals(["-metadata", "title=Hello World"], $capturedFilter->apply($audio, $format));
    }

    public function testAddArtwork()
    {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf(\FFMpeg\Filters\Audio\AddMetadataFilter::class))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMockBuilder(\FFMpeg\Format\AudioInterface::class)->getMock();

        $filters = new AudioFilters($audio);
        $filters->addMetadata(['genre' => 'Some Genre', 'artwork' => "/path/to/file.jpg"]);
        $this->assertEquals([
            "-i", "/path/to/file.jpg", "-map", "0", "-map", "1", "-metadata", "genre=Some Genre"
        ], $capturedFilter->apply($audio, $format));
    }

    public function testRemoveMetadata()
    {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf(\FFMpeg\Filters\Audio\AddMetadataFilter::class))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMockBuilder(\FFMpeg\Format\AudioInterface::class)->getMock();

        $filters = new AudioFilters($audio);
        $filters->addMetadata();
        $this->assertEquals(["-map_metadata", "-1", "-vn"], $capturedFilter->apply($audio, $format));
    }
}
