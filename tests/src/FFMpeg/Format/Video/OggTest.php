<?php

namespace FFMpeg\Format\Video;

class OggTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Ogg
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Ogg(320, 320);
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('\\FFMpeg\\Format\\DefaultVideoFormat', $this->object);
    }

    /**
     * @covers FFMpeg\Format\Video\Ogg::getAvailableAudioCodecs
     */
    public function testGetAvailableAudioCodecs()
    {
        $this->object->setAudioCodec('libvorbis');
    }

    /**
     * @covers FFMpeg\Format\Video\Ogg::getAvailableVideoCodecs
     */
    public function testGetAvailableVideoCodecs()
    {
        $this->object->setVideoCodec('libtheora');
    }

}
