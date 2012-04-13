<?php

namespace FFMpeg\Format;

require_once dirname(__FILE__) . '/../../../../src/FFMpeg/Format/X264.php';

class X264Test extends \PHPUnit_Framework_TestCase
{

    /**
     * @var X264
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new X264(320, 320);
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('\\FFMpeg\\Format\\DefaultFormat', $this->object);
    }

    /**
     * @covers FFMpeg\Format\X264::getAvailableAudioCodecs
     */
    public function testGetAvailableAudioCodecs()
    {
        $this->object->setAudioCodec('libmp3lame');
    }

    /**
     * @covers FFMpeg\Format\X264::getAvailableVideoCodecs
     */
    public function testGetAvailableVideoCodecs()
    {
        $this->object->setVideoCodec('libx264');
    }


}
