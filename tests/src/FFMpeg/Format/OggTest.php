<?php

namespace FFMpeg\Format;

require_once dirname(__FILE__) . '/../../../../src/FFMpeg/Format/Ogg.php';

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
        $this->assertInstanceOf('\\FFMpeg\\Format\\DefaultFormat', $this->object);
    }

    /**
     * @covers FFMpeg\Format\Ogg::getAvailableAudioCodecs
     */
    public function testGetAvailableAudioCodecs()
    {
        $this->object->setAudioCodec('libvorbis');
    }

    /**
     * @covers FFMpeg\Format\Ogg::getAvailableVideoCodecs
     */
    public function testGetAvailableVideoCodecs()
    {
        $this->object->setVideoCodec('libtheora');
    }

}
