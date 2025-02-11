<?php
namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Wav;

class Wav24bitTest extends FunctionalTestCase
{
    /**
     * Test that encoding an audio file to WAV at 24-bit depth
     * produces a WAV file using the correct PCM codec.
     */
    public function testWav24bitEncoding()
    {
        $ffmpeg  = FFMpeg::create();
        $ffprobe = FFProbe::create();

        $inputFile = realpath(__DIR__ . '/../files/Audio.mp3');
        $this->assertFileExists($inputFile, "The input audio file must exist for testing.");

        $audio = $ffmpeg->open($inputFile);

        $wavFormat = new Wav();
        $wavFormat->setBitDepth(24);

        $outputFile = __DIR__ . '/output/audio_24bit.wav';

        if (! file_exists(dirname($outputFile))) {
            mkdir(dirname($outputFile), 0777, true);
        }

        $audio->save($wavFormat, $outputFile);

        $this->assertFileExists($outputFile, "The 24-bit WAV file should have been created.");

        $audioStream = $ffprobe->streams($outputFile)->audios()->first();

        $this->assertEquals(
            'pcm_s24le',
            $audioStream->get('codec_name'),
            "The audio codec should be set to 'pcm_s24le' for 24-bit WAV encoding."
        );

        // If the bits_per_sample metadata is provided, check that it equals 24.
        if ($audioStream->has('bits_per_sample')) {
            $this->assertEquals(
                24,
                (int) $audioStream->get('bits_per_sample'),
                "The bits_per_sample value should be 24."
            );
        }

        unlink($outputFile);
    }
}
