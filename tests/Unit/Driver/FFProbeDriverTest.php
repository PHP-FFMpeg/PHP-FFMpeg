<?php

namespace Tests\FFMpeg\Unit\Driver;

use Alchemy\BinaryDriver\Configuration;
use FFMpeg\Driver\FFProbeDriver;
use Tests\FFMpeg\Unit\TestCase;
use Symfony\Component\Process\ExecutableFinder;

class FFProbeDriverTest extends TestCase
{
    public function setUp()
    {
        $executableFinder = new ExecutableFinder();

        $found = false;
        foreach (['avprobe', 'ffprobe'] as $name) {
            if (null !== $executableFinder->find($name)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->markTestSkipped('Neither ffprobe or avprobe found');
        }
    }

    public function testCreate()
    {
        $logger = $this->getLoggerMock();
        $ffprobe = FFProbeDriver::create([], $logger);
        $this->assertInstanceOf(\FFMpeg\Driver\FFProbeDriver::class, $ffprobe);
        $this->assertEquals($logger, $ffprobe->getProcessRunner()->getLogger());
    }

    public function testCreateWithConfig()
    {
        $conf = new Configuration();
        $ffprobe = FFProbeDriver::create($conf, $this->getLoggerMock());
        $this->assertEquals($conf, $ffprobe->getConfiguration());
    }

    /**
     * @expectedException FFMpeg\Exception\ExecutableNotFoundException
     */
    public function testCreateFailureThrowsAnException()
    {
        FFProbeDriver::create(['ffprobe.binaries' => '/path/to/nowhere']);
    }
}
