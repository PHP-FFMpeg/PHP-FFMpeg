<?php

namespace FFMpeg\Tests\Format\Video;

use FFMpeg\Tests\Format\Audio\AudioTestCase;

abstract class VideoTestCase extends AudioTestCase
{
    public function testGetVideoCodec()
    {
        $this->assertScalar($this->getFormat()->getVideoCodec());
        $this->assertContains($this->getFormat()->getVideoCodec(), $this->getFormat()->getAvailableVideoCodecs());
    }

    public function testSupportBFrames()
    {
        $this->assertInternalType('boolean', $this->getFormat()->supportBFrames());
    }

    public function testSetVideoCodec()
    {
        $format = $this->getFormat();

        foreach ($format->getAvailableVideoCodecs() as $codec) {
            $format->setVideoCodec($codec);
            $this->assertEquals($codec, $format->getVideoCodec());
        }
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidVideoCodec()
    {
        $this->getFormat()->setVideoCodec('invalid-random-video-codec');
    }

    public function testGetAvailableVideoCodecs()
    {
        $this->assertGreaterThan(0, count($this->getFormat()->getAvailableVideoCodecs()));
    }

    public function testCreateProgressListener()
    {
        $format = $this->getFormat();
        $ffprobe = $this->getFFProbeMock();

        foreach ($format->createProgressListener($ffprobe, __FILE__) as $listener) {
            $this->assertInstanceOf('FFMpeg\Format\ProgressListener\VideoProgressListener', $listener);
        }
    }

    public function testGetPasses()
    {
        $this->assertInternalType('integer', $this->getFormat()->getPasses());
        $this->assertGreaterThan(0, $this->getFormat()->getPasses());
    }

    public function testGetModulus()
    {
        $this->assertInternalType('integer', $this->getFormat()->getModulus());
        $this->assertGreaterThan(0, $this->getFormat()->getModulus());
        $this->assertEquals(0, $this->getFormat()->getModulus() % 2);
    }
}
