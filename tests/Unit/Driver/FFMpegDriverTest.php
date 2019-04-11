<?php
declare(strict_types=1);

namespace Tests\FFMpeg\Unit\Driver;

use Alchemy\BinaryDriver\Configuration;
use FFMpeg\Driver\FFMpegDriver;
use Tests\FFMpeg\Unit\TestCase;
use Symfony\Component\Process\ExecutableFinder;
use FFMpeg\Exception\InvalidArgumentException;

class FFMpegDriverTest extends TestCase
{
    public function setUp()
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
        $this->assertInstanceOf(\FFMpeg\Driver\FFMpegDriver::class, $ffmpeg);
        $this->assertEquals($logger, $ffmpeg->getProcessRunner()->getLogger());
    }

    public function testCreateWithConfig()
    {
        $conf = new Configuration();
        $ffmpeg = FFMpegDriver::create($this->getLoggerMock(), $conf);
        $this->assertEquals($conf, $ffmpeg->getConfiguration());
    }

    public function testInvalidConfigParameter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The $configuration Parameter must either be an array or an instance of ConfigurationInterface, string given.');

        FFMpegDriver::create($this->getLoggerMock(), 'dumb string');
    }

    /**
     * @expectedException FFMpeg\Exception\ExecutableNotFoundException
     */
    public function testCreateFailureThrowsAnException()
    {
        FFMpegDriver::create($this->getLoggerMock(), ['ffmpeg.binaries' => '/path/to/nowhere']);
    }
}
