<?php

namespace FFMpeg\Format\Video;

class WebMTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var WebM
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new WebM();
        $this->object->setDimensions(320, 320);
    }

    /**
     * @covers FFMpeg\Format\Video\WebM::getAvailableAudioCodecs
     */
    public function testGetAvailableAudioCodecs()
    {
        $this->object->setAudioCodec('libvorbis');
    }

    /**
     * @covers FFMpeg\Format\Video\WebM::getAvailableVideoCodecs
     */
    public function testGetAvailableVideoCodecs()
    {
        $this->object->setVideoCodec('libvpx');
    }

    /**
     * @covers FFMpeg\Format\Video\WebM::getExtraParams
     */
    public function testGetExtraParams()
    {
        $this->assertTrue(is_scalar($this->object->getExtraParams()));
    }

}
