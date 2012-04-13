<?php

namespace FFMpeg\Format;

class DefaultVideoFormatTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DefaultVideoFormat
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new DefaultVideoFormatTester(320, 240);
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::__construct
     * @covers FFMpeg\Format\DefaultVideoFormat::getWidth
     * @covers FFMpeg\Format\DefaultVideoFormat::getHeight
     */
    public function testConstruct()
    {
        $this->assertEquals(320, $this->object->getWidth());
        $this->assertEquals(240, $this->object->getHeight());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setDimensions
     */
    public function testSetDimensions()
    {
        $this->object->setDimensions(240, 640);
        $this->assertEquals(240, $this->object->getWidth());
        $this->assertEquals(640, $this->object->getHeight());

        $this->object->setDimensions(242, 638);
        $this->assertEquals(240, $this->object->getWidth());
        $this->assertEquals(640, $this->object->getHeight());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setDimensions
     * @dataProvider getWrongDimensions
     * @expectedException \InvalidArgumentException
     */
    public function testWrongDimensions($width, $height)
    {
        $this->object->setDimensions($width, $height);
    }

    /**
     * Data provider for testWrongDimensions
     *
     * @return array
     */
    public function getWrongDimensions()
    {
        return array(
          array(0, 240),
          array(240, 0),
          array(-5, 240),
          array(240, -5),
          array(-5, -5),
          array(0, 0)
        );
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::getFrameRate
     */
    public function testGetFrameRate()
    {
        $this->assertEquals(25, $this->object->getFrameRate());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setFrameRate
     */
    public function testSetFrameRate()
    {
        $this->object->setFrameRate(12);
        $this->assertEquals(12, $this->object->getFrameRate());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setFrameRate
     * @dataProvider getWrongFrameRates
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongFrameRates($framerate)
    {
        $this->object->setFrameRate($framerate);
    }

    /**
     * Data provider for testWrongFrameRates
     *
     * @return array
     */
    public function getWrongFramerates()
    {
        return array(array(-5), array(0));
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::getVideoCodec
     */
    public function testGetVideoCodec()
    {
        $this->assertEquals('videocodec2', $this->object->getVideoCodec());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setVideoCodec
     */
    public function testSetVideoCodec()
    {
        $this->object->setVideoCodec('videocodec2');
        $this->assertEquals('videocodec2', $this->object->getVideoCodec());
        $this->object->setVideoCodec('videocodec1');
        $this->assertEquals('videocodec1', $this->object->getVideoCodec());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setVideoCodec
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongVideoCodec()
    {
        $this->object->setVideoCodec('videocodec4');
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::getGOPsize
     */
    public function testGetGOPsize()
    {
        $this->assertEquals(25, $this->object->getGOPsize());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setGOPsize
     */
    public function testSetGOPsize()
    {
        $this->object->setGOPsize(100);
        $this->assertEquals(100, $this->object->getGOPsize());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::setGOPsize
     * @dataProvider getWrongGOPsize
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongGOPSize($GOP)
    {
        $this->object->setGOPsize($GOP);
    }

    public function getWrongGOPsize()
    {
        return array(array(-5), array(0));
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::getMultiple
     */
    public function testGetMultiple()
    {
        $this->assertEquals(320, $this->object->getMultiple(321, 16));
        $this->assertEquals(320, $this->object->getMultiple(319, 16));
        $this->assertEquals(320, $this->object->getMultiple(313, 16));
        $this->assertEquals(304, $this->object->getMultiple(312, 16));
        $this->assertEquals(336, $this->object->getMultiple(329, 16));
        $this->assertEquals(16, $this->object->getMultiple(8, 16));
    }

    /**
     * @covers FFMpeg\Format\DefaultVideoFormat::getKiloBitrate
     */
    public function testGetKiloBitrate()
    {
        $this->assertEquals(1000, $this->object->getKiloBitrate());
    }

}

class DefaultVideoFormatTester extends DefaultVideoFormat
{

    protected $audioCodec = 'audiocodec1';
    protected $videoCodec = 'videocodec2';

    public function getAvailableAudioCodecs()
    {
        return array('audiocodec1', 'audiocodec2', 'audiocodec3');
    }

    public function getAvailableVideoCodecs()
    {
        return array('videocodec1', 'videocodec2');
    }

    public function getExtraParams()
    {
        return '-f format';
    }

    public function getMultiple($value, $multiple)
    {
        return parent::getMultiple($value, $multiple);
    }

}
