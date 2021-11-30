<?php

namespace Tests\FFMpeg\Unit\Driver;

use Alchemy\BinaryDriver\Configuration;
use FFMpeg\Driver\FFMpegDriver;
use Symfony\Component\Process\ExecutableFinder;
use Tests\FFMpeg\Unit\TestCase;

class FFMpegDriverTest extends TestCase
{
    public function setUp(): void
    {
        $executableFinder = new ExecutableFinder();

        $found = false;
        foreach (['avconv', 'ffmpeg'] as $name) {
            if (null !== $executableFinder->find($name)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->markTestSkipped('Neither ffmpeg or avconv found');
        }
    }

    public function testCreate()
    {
        $logger = $this->getLoggerMock();
        $ffmpeg = FFMpegDriver::create($logger, []);
        $this->assertInstanceOf('FFMpeg\Driver\FFMpegDriver', $ffmpeg);
        $this->assertEquals($logger, $ffmpeg->getProcessRunner()->getLogger());
    }

    public function testCreateWithConfig()
    {
        $conf = new Configuration();
        $ffmpeg = FFMpegDriver::create($this->getLoggerMock(), $conf);
        $this->assertEquals($conf, $ffmpeg->getConfiguration());
    }

    public function testCreateFailureThrowsAnException()
    {
        $this->expectException('\FFMpeg\Exception\ExecutableNotFoundException');
        FFMpegDriver::create($this->getLoggerMock(), ['ffmpeg.binaries' => '/path/to/nowhere']);
    }
}
