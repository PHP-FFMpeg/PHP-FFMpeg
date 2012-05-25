<?php

namespace FFMpeg\Format\Video;

class X264Test extends \PHPUnit_Framework_TestCase
{

    /**
     * @var X264
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new X264();
        $this->object->setDimensions(320, 320);
    }

    /**
     * @covers FFMpeg\Format\Video\X264::getAvailableAudioCodecs
     */
    public function testGetAvailableAudioCodecs()
    {
        $this->object->setAudioCodec('libmp3lame');
    }

    /**
     * @covers FFMpeg\Format\Video\X264::getAvailableVideoCodecs
     */
    public function testGetAvailableVideoCodecs()
    {
        $this->object->setVideoCodec('libx264');
    }

}
