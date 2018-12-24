<?php
namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\{
    DefaultAudio,
    Aac
};

class AacTest extends AudioTestCase
{
    public function getFormat(): DefaultAudio
    {
        return new Aac();
    }
}
