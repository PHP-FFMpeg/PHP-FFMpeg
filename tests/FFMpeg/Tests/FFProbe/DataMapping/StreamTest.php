<?php

namespace FFMpeg\Tests\FFProbe\DataMapping;

use FFMpeg\Tests\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;

class StreamTest extends TestCase
{
    /**
     * @dataProvider provideAudioCases
     */
    public function testIsAudio($isAudio, $properties)
    {
        $stream = new Stream($properties);
        $this->assertTrue($isAudio === $stream->isAudio());
    }

    public function provideAudioCases()
    {
        return array(
            array(true, array('codec_type' => 'audio')),
            array(false, array('codec_type' => 'video')),
        );
    }

    /**
     * @dataProvider provideVideoCases
     */
    public function testIsVideo($isVideo, $properties)
    {
        $stream = new Stream($properties);
        $this->assertTrue($isVideo === $stream->isVideo());
    }

    public function provideVideoCases()
    {
        return array(
            array(true, array('codec_type' => 'video')),
            array(false, array('codec_type' => 'audio')),
        );
    }
}
