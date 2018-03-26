<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Media\Audio;

class AudioConcatenationTest extends FunctionalTestCase
{
    public function testSimpleAudioFileConcatTest()
    {
        $ffmpeg = $this->getFFMpeg();
        
        $files = [
            __DIR__ . '/../files/Jahzzar_-_05_-_Siesta.mp3',
            __DIR__ . '/../files/02_-_Favorite_Secrets.mp3',
        ];

        $audio = $ffmpeg->open(reset($files));

        $this->assertInstanceOf('FFMpeg\Media\Audio', $audio);
        
        $outputDirectory = __DIR__ . '/output/';
        clearstatcache($outputDirectory);
        $filename = $outputDirectory . 'concat-output.mp3';

        $audio->concat($files)->saveFromSameCodecs($filename, TRUE);
        
        $this->assertFileExists($filename);
        unlink($filename);
    }
}