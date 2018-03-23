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

        $audio = $ffmpeg->open(current($files));

        $this->assertInstanceOf('FFMpeg\Media\Audio', $audio);
        
        $filename = __DIR__ . '/output/concat-output.mp3';

        if (is_file($filename)) {
            unlink($filename);
        }

        $audio->concat($files)->saveFromSameCodecs($filename, TRUE);
        
        $this->assertFileExists($filename);
        unlink($filename);
    }
}