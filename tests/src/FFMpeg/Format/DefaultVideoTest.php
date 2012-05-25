<?php

namespace FFMpeg\Format;

class DefaultVideoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DefaultVideo
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new DefaultVideoTester();
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setDimensions
     * @covers FFMpeg\Format\DefaultVideo::getWidth
     * @covers FFMpeg\Format\DefaultVideo::getHeight
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
     * @covers FFMpeg\Format\DefaultVideo::setDimensions
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
     * @covers FFMpeg\Format\DefaultVideo::getFrameRate
     */
    public function testGetFrameRate()
    {
        $this->assertEquals(25, $this->object->getFrameRate());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setFrameRate
     */
    public function testSetFrameRate()
    {
        $this->object->setFrameRate(12);
        $this->assertEquals(12, $this->object->getFrameRate());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setFrameRate
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
     * @covers FFMpeg\Format\DefaultVideo::getVideoCodec
     */
    public function testGetVideoCodec()
    {
        $this->assertEquals('videocodec2', $this->object->getVideoCodec());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setVideoCodec
     */
    public function testSetVideoCodec()
    {
        $this->object->setVideoCodec('videocodec2');
        $this->assertEquals('videocodec2', $this->object->getVideoCodec());
        $this->object->setVideoCodec('videocodec1');
        $this->assertEquals('videocodec1', $this->object->getVideoCodec());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setVideoCodec
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongVideoCodec()
    {
        $this->object->setVideoCodec('videocodec4');
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::getGOPsize
     */
    public function testGetGOPsize()
    {
        $this->assertEquals(25, $this->object->getGOPsize());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setGOPsize
     */
    public function testSetGOPsize()
    {
        $this->object->setGOPsize(100);
        $this->assertEquals(100, $this->object->getGOPsize());
    }

    /**
     * @covers FFMpeg\Format\DefaultVideo::setGOPsize
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
     * @covers FFMpeg\Format\DefaultVideo::getMultiple
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
     * @covers FFMpeg\Format\DefaultVideo::getKiloBitrate
     */
    public function testGetKiloBitrate()
    {
        $this->assertEquals(1000, $this->object->getKiloBitrate());
    }

}

class DefaultVideoTester extends DefaultVideo
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
