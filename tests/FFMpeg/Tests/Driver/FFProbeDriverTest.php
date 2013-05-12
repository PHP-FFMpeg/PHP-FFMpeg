<?php

namespace FFMpeg\Tests\Driver;

use Alchemy\BinaryDriver\Configuration;
use FFMpeg\Driver\FFProbeDriver;
use FFMpeg\Tests\TestCase;
use Symfony\Component\Process\ExecutableFinder;

class FFProbeDriverTest extends TestCase
{
    public function setUp()
    {
        $executableFinder = new ExecutableFinder();

        $found = false;
        foreach (array('avprobe', 'ffprobe') as $name) {
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
        $ffprobe = FFProbeDriver::create($logger, array());
        $this->assertInstanceOf('FFMpeg\Driver\FFProbeDriver', $ffprobe);
        $this->assertEquals($logger, $ffprobe->getProcessRunner()->getLogger());
    }

    public function testCreateWithConfig()
    {
        $conf = new Configuration();
        $ffprobe = FFProbeDriver::create($this->getLoggerMock(), $conf);
        $this->assertEquals($conf, $ffprobe->getConfiguration());
    }
}