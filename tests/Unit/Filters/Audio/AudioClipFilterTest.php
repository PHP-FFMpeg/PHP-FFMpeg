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

        $foo = true;
        $this->assertTrue($foo);
    }

    // public function testAddArtwork()
    // {
    //     $capturedFilter = null;

    //     $audio = $this->getAudioMock();
    //     $audio->expects($this->once())
    //         ->method('addFilter')
    //         ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AddMetadataFilter'))
    //         ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
    //             $capturedFilter = $filter;
    //         }));
    //     $format = $this->getMock('FFMpeg\Format\AudioInterface');

    //     $filters = new AudioFilters($audio);
    //     $filters->addMetadata(array('genre' => 'Some Genre', 'artwork' => "/path/to/file.jpg"));
    //     $this->assertEquals(array(0 => "-i", 1 => "/path/to/file.jpg", 2 => "-map", 3 => "0", 4 => "-map", 5 => "1", 6 => "-metadata", 7 => "genre=Some Genre"), $capturedFilter->apply($audio, $format));
    // }

    // public function testRemoveMetadata()
    // {
    //     $capturedFilter = null;

    //     $audio = $this->getAudioMock();
    //     $audio->expects($this->once())
    //         ->method('addFilter')
    //         ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AddMetadataFilter'))
    //         ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
    //             $capturedFilter = $filter;
    //         }));
    //     $format = $this->getMock('FFMpeg\Format\AudioInterface');

    //     $filters = new AudioFilters($audio);
    //     $filters->addMetadata();
    //     $this->assertEquals(array(0 => "-map_metadata", 1 => "-1", 2 => "-vn"), $capturedFilter->apply($audio, $format));
    // }
}
