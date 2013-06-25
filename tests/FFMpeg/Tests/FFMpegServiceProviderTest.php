<?php

namespace FFMpeg\Tests;

use FFMpeg\FFMpegServiceProvider;
use Silex\Application;

class FFMpegServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testWithConfig()
    {
        $app = new Application();
        $app->register(new FFMpegServiceProvider(), array(
            'ffmpeg.configuration' => array(
                'ffmpeg.threads'   => 12,
                'ffmpeg.timeout'   => 10666,
                'ffprobe.timeout'  => 4242,
            )
        ));

        $this->assertInstanceOf('FFMpeg\FFMpeg', $app['ffmpeg']);
        $this->assertSame($app['ffmpeg'], $app['ffmpeg.ffmpeg']);
        $this->assertInstanceOf('FFMpeg\FFProbe', $app['ffmpeg.ffprobe']);

        $this->assertEquals(12, $app['ffmpeg']->getFFMpegDriver()->getConfiguration()->get('ffmpeg.threads'));
        $this->assertEquals(10666, $app['ffmpeg']->getFFMpegDriver()->getProcessBuilderFactory()->getTimeout());
        $this->assertEquals(4242, $app['ffmpeg.ffprobe']->getFFProbeDriver()->getProcessBuilderFactory()->getTimeout());
    }

    public function testWithoutConfig()
    {
        $app = new Application();
        $app->register(new FFMpegServiceProvider());

        $this->assertInstanceOf('FFMpeg\FFMpeg', $app['ffmpeg']);
        $this->assertSame($app['ffmpeg'], $app['ffmpeg.ffmpeg']);
        $this->assertInstanceOf('FFMpeg\FFProbe', $app['ffmpeg.ffprobe']);

        $this->assertEquals(4, $app['ffmpeg']->getFFMpegDriver()->getConfiguration()->get('ffmpeg.threads'));
        $this->assertEquals(300, $app['ffmpeg']->getFFMpegDriver()->getProcessBuilderFactory()->getTimeout());
        $this->assertEquals(30, $app['ffmpeg.ffprobe']->getFFProbeDriver()->getProcessBuilderFactory()->getTimeout());
    }

    public function testWithFFMpegBinaryConfig()
    {
        $app = new Application();
        $app->register(new FFMpegServiceProvider(), array(
            'ffmpeg.configuration' => array(
                'ffmpeg.binaries' => '/path/to/ffmpeg',
            )
        ));

        $this->setExpectedException('Alchemy\BinaryDriver\Exception\ExecutableNotFoundException', 'Executable not found, proposed : /path/to/ffmpeg');
        $app['ffmpeg'];
    }

    public function testWithFFMprobeBinaryConfig()
    {
        $app = new Application();
        $app->register(new FFMpegServiceProvider(), array(
            'ffmpeg.configuration' => array(
                'ffprobe.binaries' => '/path/to/ffprobe',
            )
        ));

        $this->setExpectedException('Alchemy\BinaryDriver\Exception\ExecutableNotFoundException', 'Executable not found, proposed : /path/to/ffprobe');
        $app['ffmpeg.ffprobe'];
    }
}
