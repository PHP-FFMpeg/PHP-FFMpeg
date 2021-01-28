<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Media\Spectrum;

class AudioImagingTest extends FunctionalTestCase
{
    /**
     * Path prefix to avoid conflicts with another tests.
     */
    const OUTPUT_PATH_PREFIX = 'output/audio_';

    public function testgenerateSpectrumImage()
    {
        $ffmpeg = FFMpeg::create();
        $ffprobe = FFProbe::create();
        $audio = $ffmpeg->open(realpath(__DIR__ . '/../files/Audio.mp3'));
        $spectrum = new Spectrum($audio, $ffmpeg->getFFMpegDriver(), $ffprobe, 1024, 1024);
        $spectrum->setLegend(false)
            ->setOrientation('horizontal')
            ->setColor('fiery');
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'spectrum.png';
        $spectrum->save($output);
        $this->assertFileExists($output);
        unlink($output);
    }
}
