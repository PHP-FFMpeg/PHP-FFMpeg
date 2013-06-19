<?php

namespace FFMpeg\Tests\Format\Audio;

use FFMpeg\Tests\TestCase;
use FFMpeg\Format\Audio\DefaultAudio;

abstract class AudioTestCase extends TestCase
{
    public function testExtraParams()
    {
        foreach ($this->getFormat()->getExtraParams() as $param) {
            $this->assertScalar($param);
        }
    }

    public function testGetAudioCodec()
    {
        $this->assertScalar($this->getFormat()->getAudioCodec());
        $this->assertContains($this->getFormat()->getAudioCodec(), $this->getFormat()->getAvailableAudioCodecs());
    }

    public function testSetAudioCodec()
    {
        $format = $this->getFormat();

        foreach ($format->getAvailableAudioCodecs() as $codec) {
            $format->setAudioCodec($codec);
            $this->assertEquals($codec, $format->getAudioCodec());
        }
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidAudioCodec()
    {
        $this->getFormat()->setAudioCodec('invalid-random-audio-codec');
    }

    public function testGetAvailableAudioCodecs()
    {
        $this->assertGreaterThan(0, count($this->getFormat()->getAvailableAudioCodecs()));
    }

    public function testGetKiloBitrate()
    {
        $this->assertInternalType('integer', $this->getFormat()->getKiloBitrate());
    }

    public function testSetKiloBitrate()
    {
        $format = $this->getFormat();
        $format->setKiloBitrate(256);
        $this->assertEquals(256, $format->getKiloBitrate());
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidKiloBitrate()
    {
        $this->getFormat()->setKiloBitrate(0);
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetNegativeKiloBitrate()
    {
        $this->getFormat()->setKiloBitrate(-10);
    }

    public function testCreateProgressListener()
    {
        $format = $this->getFormat();
        $ffprobe = $this->getFFProbeMock();

        foreach ($format->createProgressListener($ffprobe, __FILE__) as $listener) {
            $this->assertInstanceOf('FFMpeg\Format\ProgressListener\AudioProgressListener', $listener);
        }
    }

    /**
     * @return DefaultAudio
     */
    abstract public function getFormat();
}
