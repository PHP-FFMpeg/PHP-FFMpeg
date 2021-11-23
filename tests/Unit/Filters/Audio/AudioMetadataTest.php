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
            ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AddMetadataFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        $filters = new AudioFilters($audio);
        $filters->addMetadata(['title' => 'Hello World']);
        $this->assertEquals([0 => '-metadata', 1 => 'title=Hello World'], $capturedFilter->apply($audio, $format));
    }

    public function testAddArtwork()
    {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AddMetadataFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        $filters = new AudioFilters($audio);
        $filters->addMetadata(['genre' => 'Some Genre', 'artwork' => '/path/to/file.jpg']);
        $this->assertEquals([0 => '-i', 1 => '/path/to/file.jpg', 2 => '-map', 3 => '0', 4 => '-map', 5 => '1', 6 => '-metadata', 7 => 'genre=Some Genre'], $capturedFilter->apply($audio, $format));
        $this->assertEquals([0 => '-i', 1 => '/path/to/file.jpg', 2 => '-map', 3 => '0', 4 => '-map', 5 => '1', 6 => '-metadata', 7 => 'genre=Some Genre'], $capturedFilter->apply($audio, $format));
    }

    public function testRemoveMetadata()
    {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AddMetadataFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        $filters = new AudioFilters($audio);
        $filters->addMetadata();
        $this->assertEquals([0 => '-map_metadata', 1 => '-1', 2 => '-vn'], $capturedFilter->apply($audio, $format));
    }
}
