<?php

namespace FFMpeg\Format;

require_once dirname(__FILE__) . '/../../../../src/FFMpeg/Format/WebM.php';

class WebMTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebM
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new WebM(320, 320);
    }

    /**
     * @covers FFMpeg\Format\WebM::getAvailableAudioCodecs
     */
    public function testGetAvailableAudioCodecs()
    {
        $this->object->setAudioCodec('libvorbis');
    }

    /**
     * @covers FFMpeg\Format\WebM::getAvailableVideoCodecs
     */
    public function testGetAvailableVideoCodecs()
    {
        $this->object->setVideoCodec('libvpx');
    }

    /**
     * @covers FFMpeg\Format\WebM::getExtraParams
     */
    public function testGetExtraParams()
    {
        $this->assertTrue(is_scalar($this->object->getExtraParams()));
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('\\FFMpeg\\Format\\DefaultFormat', $this->object);
    }

}
