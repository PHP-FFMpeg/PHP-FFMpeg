<?php

namespace FFMpeg;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Silex\Application;

class FFMpegServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    private function getApplication()
    {
        return new Application();
    }

    /**
     * @covers FFMpeg\FFMpegServiceProvider::register
     */
    public function testRegister()
    {
        $app = $this->getApplication();

        $app->register(new FFMpegServiceProvider());

        $this->assertInstanceOf('\\FFMpeg\\FFProbe', $app['ffmpeg.ffprobe']);
        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $app['ffmpeg.ffmpeg']);
    }

    /**
     * @expectedException FFMpeg\Exception\BinaryNotFoundException
     * @covers FFMpeg\FFMpegServiceProvider::register
     */
    public function testRegisterFFMpegFails()
    {
        $app = $this->getApplication();
        $app->register(new FFMpegServiceProvider(), array('ffmpeg.ffmpeg.binary' => '/path/to/no/ffmpeg'));

        $app['ffmpeg.ffmpeg'];
    }

    /**
     * @expectedException FFMpeg\Exception\BinaryNotFoundException
     * @covers FFMpeg\FFMpegServiceProvider::register
     */
    public function testRegisterFFProbeFails()
    {
        $app = $this->getApplication();
        $app->register(new FFMpegServiceProvider(), array('ffmpeg.ffprobe.binary' => '/path/to/no/ffprobe'));

        $app['ffmpeg.ffprobe'];
    }

    /**
     * @covers FFMpeg\FFMpegServiceProvider::register
     */
    public function testRegisterCustomLogger()
    {
        $app = $this->getApplication();
        $app['logger'] = $app->share(function(Application $app){
           $logger =  new Logger('tests');
           $logger->pushHandler(new NullHandler());

           return $logger;
        });
        $app->register(new FFMpegServiceProvider());

        $this->assertInstanceOf('\\FFMpeg\\FFProbe', $app['ffmpeg.ffprobe']);
        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $app['ffmpeg.ffmpeg']);
    }

    /**
     * @covers FFMpeg\FFMpegServiceProvider::register
     */
    public function testCustomThreadsFFMpeg()
    {
        $app = $this->getApplication();
        $app->register(new FFMpegServiceProvider(), array('ffmpeg.threads'=>18));

        $this->assertEquals(18, $app['ffmpeg.ffmpeg']->getThreads());
    }
}

