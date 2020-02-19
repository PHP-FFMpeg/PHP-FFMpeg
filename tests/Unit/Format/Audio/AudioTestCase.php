<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Format\Audio\DefaultAudio;

abstract class AudioTestCase extends TestCase
{
    public function testExtraParams()
    {
        $extraParams = $this->getFormat()->getExtraParams();

        $this->assertIsArray($extraParams);

        foreach ($extraParams as $param) {
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

    public function testSetInvalidAudioCodec()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $this->getFormat()->setAudioCodec('invalid-random-audio-codec');
    }

    public function testGetAvailableAudioCodecs()
    {
        $this->assertGreaterThan(0, count($this->getFormat()->getAvailableAudioCodecs()));
    }

    public function testGetAudioKiloBitrate()
    {
        $this->assertIsInt($this->getFormat()->getAudioKiloBitrate());
    }

    public function testSetAudioKiloBitrate()
    {
        $format = $this->getFormat();
        $format->setAudioKiloBitrate(256);
        $this->assertEquals(256, $format->getAudioKiloBitrate());
    }

    public function testSetInvalidKiloBitrate()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $this->getFormat()->setAudioKiloBitrate(0);
    }

    public function testSetNegativeKiloBitrate()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $this->getFormat()->setAudioKiloBitrate(-10);
    }

    public function testGetAudioChannels()
    {
        $this->assertNull($this->getFormat()->getAudioChannels());
    }

    public function testSetAudioChannels()
    {
        $format = $this->getFormat();
        $format->setAudioChannels(2);
        $this->assertEquals(2, $format->getAudioChannels());
    }

    public function testSetInvalidChannels()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $this->getFormat()->setAudioChannels(0);
    }

    public function testSetNegativeChannels()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $this->getFormat()->setAudioChannels(-10);
    }

    public function testCreateProgressListener()
    {
        $media = $this->getMockBuilder('FFMpeg\Media\MediaTypeInterface')->getMock();
        $media->expects($this->any())
            ->method('getPathfile')
            ->will($this->returnValue(__FILE__));
        $format = $this->getFormat();
        $ffprobe = $this->getFFProbeMock();

        foreach ($format->createProgressListener($media, $ffprobe, 1, 3) as $listener) {
            $this->assertInstanceOf('FFMpeg\Format\ProgressListener\AudioProgressListener', $listener);
            $this->assertSame($ffprobe, $listener->getFFProbe());
            $this->assertSame(__FILE__, $listener->getPathfile());
            $this->assertSame(1, $listener->getCurrentPass());
            $this->assertSame(3, $listener->getTotalPass());
        }
    }

    /**
     * @return DefaultAudio
     */
    abstract public function getFormat();
}
