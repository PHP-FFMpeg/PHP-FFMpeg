<?php

namespace FFMpeg;

class FFProbeTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FFProbe
     */
    protected $object;

    public function setUp()
    {
        $this->object = FFProbe::load();
    }

    /**
     * @covers FFMpeg\FFProbe::probeFormat
     * @covers FFMpeg\FFProbe::probeStreams
     * @covers FFMpeg\FFProbe::executeProbe
     */
    public function testProbe()
    {
        $this->object->probeFormat(__DIR__ . '/../../files/Test.ogv');
        $this->object->probeStreams(__DIR__ . '/../../files/Test.ogv');
    }

    /**
     * @covers FFMpeg\FFProbe::probeFormat
     * @covers FFMpeg\FFProbe::executeProbe
     * @expectedException \RuntimeException
     */
    public function testProbeInvalidFile()
    {
        $this->object->probeFormat(__DIR__ . '/../../files/WrongFile.mp4');
    }

    /**
     * @covers FFMpeg\FFProbe::probeStreams
     * @covers FFMpeg\FFProbe::executeProbe
     * @expectedException \FFMpeg\Exception\RuntimeException
     */
    public function testProbeStreamsInvalidFile()
    {
        $this->object->probeStreams(__DIR__ . '/../../files/WrongFile.mp4');
    }

    /**
     * @covers FFMpeg\FFProbe::probeStreams
     * @covers FFMpeg\FFProbe::executeProbe
     * @expectedException \InvalidArgumentException
     */
    public function testProbeStreamsInvalidPathFile()
    {
        $this->object->probeStreams(__DIR__ . '/../../files/unknown.file');
    }

    /**
     * @covers FFMpeg\FFProbe::probeFormat
     * @covers FFMpeg\FFProbe::executeProbe
     * @expectedException \InvalidArgumentException
     */
    public function testProbeFormatInvalidPathFile()
    {
        $this->object->probeFormat(__DIR__ . '/../../files/unknown.file');
    }

}
