<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;

class FFMpegTest extends FunctionalTestCase
{
    public function testOpenOnAudio()
    {
        $ffmpeg = FFMpeg::create();
        $audio = $ffmpeg->open(__DIR__ . '/../files/Audio.mp3');
        $this->assertInstanceOf('FFMpeg\Media\Audio', $audio);
        $this->assertNotInstanceOf('FFMpeg\Media\Video', $audio);
    }

    public function testOpenOnAudioWithCover()
    {
        $ffmpeg = FFMpeg::create();
        $audio = $ffmpeg->open(__DIR__ . '/../files/audio-with-cover.mp3');
        $this->assertInstanceOf('FFMpeg\Media\Audio', $audio);
        $this->assertNotInstanceOf('FFMpeg\Media\Video', $audio);
    }

    public function testOpenOnVideo()
    {
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open(__DIR__ . '/../files/Test.ogv');
        $this->assertInstanceOf('FFMpeg\Media\Audio', $video);
        $this->assertInstanceOf('FFMpeg\Media\Video', $video);
    }
}
