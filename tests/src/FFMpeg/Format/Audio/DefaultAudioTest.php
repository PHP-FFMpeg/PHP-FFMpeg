<?php

namespace FFMpeg\Format\Audio;

class DefaultAudioTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DefaultAudio
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new DefaultAudioTester();
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::getExtraParams
     */
    public function testGetExtraParams()
    {
        $this->assertEquals('-f format', $this->object->getExtraParams());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::getAudioCodec
     */
    public function testGetAudioCodec()
    {
        $this->assertEquals('audiocodec1', $this->object->getAudioCodec());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::setAudioCodec
     */
    public function testSetAudioCodec()
    {
        $this->object->setAudioCodec('audiocodec2');
        $this->assertEquals('audiocodec2', $this->object->getAudioCodec());
        $this->object->setAudioCodec('audiocodec1');
        $this->assertEquals('audiocodec1', $this->object->getAudioCodec());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::setAudioCodec
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongAudioCodec()
    {
        $this->object->setAudioCodec('audiocodec4');
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::getAudioSampleRate
     */
    public function testGetAudioSampleRate()
    {
        $this->assertEquals(44100, $this->object->getAudioSampleRate());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::setAudioSampleRate
     */
    public function testSetAudioSampleRate()
    {
        $this->object->setAudioSampleRate(22050);
        $this->assertEquals(22050, $this->object->getAudioSampleRate());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::setAudioSampleRate
     * @expectedException \InvalidArgumentException
     * @dataProvider getWrongAudioSampleRate
     */
    public function testSetWrongAudioSampleRate($samplerate)
    {
        $this->object->setAudioSampleRate($samplerate);
    }

    public function getWrongAudioSampleRate()
    {
        return array(array(-5), array(0));
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::getKiloBitrate
     */
    public function testGetKiloBitrate()
    {
        $this->assertEquals(128, $this->object->getKiloBitrate());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::setKiloBitrate
     */
    public function testSetKiloBitrate()
    {
        $this->object->setKiloBitrate(500);
        $this->assertEquals(500, $this->object->getKiloBitrate());
    }

    /**
     * @covers FFMpeg\Format\Audio\DefaultAudio::setKiloBitrate
     * @dataProvider getWrongKiloBitrate
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongKiloBitrate($kbrate)
    {
        $this->object->setKiloBitrate($kbrate);
    }

    public function getWrongKiloBitrate()
    {
        return array(array(-5), array(0));
    }

}

class DefaultAudioTester extends DefaultAudio
{

    protected $audioCodec = 'audiocodec1';

    public function getAvailableAudioCodecs()
    {
        return array('audiocodec1', 'audiocodec2', 'audiocodec3');
    }

    public function getExtraParams()
    {
        return '-f format';
    }

}
