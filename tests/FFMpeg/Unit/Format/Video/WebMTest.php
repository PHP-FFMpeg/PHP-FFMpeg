<?php
namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\WebM;

class WebMTest extends VideoTestCase
{
    public function getFormat()
    {
        return new WebM();
    }

    // Returns array containing exactly three audio codecs
    public function testGetAvailableAudioCodecs(): void
    {
        $webm = new WebM();

        $codecs = $webm->getAvailableAudioCodecs();

        $this->assertCount(3, $codecs);
        $this->assertEquals(['copy', 'libvorbis', 'libopus'], $codecs);
    }
}
