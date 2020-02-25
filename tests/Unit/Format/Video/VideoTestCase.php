<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use Tests\FFMpeg\Unit\Format\Audio\AudioTestCase;

abstract class VideoTestCase extends AudioTestCase
{
    public function testGetVideoCodec()
    {
        $this->assertScalar($this->getFormat()->getVideoCodec());
        $this->assertContains($this->getFormat()->getVideoCodec(), $this->getFormat()->getAvailableVideoCodecs());
    }

    public function testSupportBFrames()
    {
        $this->assertIsBool($this->getFormat()->supportBFrames());
    }

    public function testSetVideoCodec()
    {
        $format = $this->getFormat();

        foreach ($format->getAvailableVideoCodecs() as $codec) {
            $format->setVideoCodec($codec);
            $this->assertEquals($codec, $format->getVideoCodec());
        }
    }

    public function testGetKiloBitrate()
    {
        $this->assertIsInt($this->getFormat()->getKiloBitrate());
    }

    public function testSetKiloBitrate()
    {
        $format = $this->getFormat();
        $format->setKiloBitrate(2560);
        $this->assertEquals(2560, $format->getKiloBitrate());
    }

    public function testSetInvalidVideoCodec()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $this->getFormat()->setVideoCodec('invalid-random-video-codec');
    }

    public function testGetAvailableVideoCodecs()
    {
        $this->assertGreaterThan(0, count($this->getFormat()->getAvailableVideoCodecs()));
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
            $this->assertInstanceOf('FFMpeg\Format\ProgressListener\VideoProgressListener', $listener);
            $this->assertSame($ffprobe, $listener->getFFProbe());
            $this->assertSame(__FILE__, $listener->getPathfile());
            $this->assertSame(1, $listener->getCurrentPass());
            $this->assertSame(3, $listener->getTotalPass());
        }
    }

    public function testGetPasses()
    {
        $this->assertIsInt($this->getFormat()->getPasses());
        $this->assertGreaterThan(0, $this->getFormat()->getPasses());
    }

    public function testGetModulus()
    {
        $this->assertIsInt($this->getFormat()->getModulus());
        $this->assertGreaterThan(0, $this->getFormat()->getModulus());
        $this->assertEquals(0, $this->getFormat()->getModulus() % 2);
    }
}
