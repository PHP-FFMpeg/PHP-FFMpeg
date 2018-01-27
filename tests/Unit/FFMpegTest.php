<?php

namespace Tests\FFMpeg\Unit;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\FFProbe\DataMapping\Stream;

class FFMpegTest extends TestCase {

    /**
     * @expectedException \FFMpeg\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unable to detect file format, only audio and video supported
     */
    public function testOpenInvalid()
    {
        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $this->getFFProbeMock());
        $ffmpeg->open('/path/to/unknown/file');
    }

    public function testOpenAudio() {
        $streams = $this->getStreamCollectionMock();
        $streams->expects($this->once())
            ->method('getAudioStreams')
            ->will($this->returnValue(new StreamCollection([new Stream])));
        $streams->expects($this->once())
            ->method('getVideoStreams')
            ->will($this->returnValue(new StreamCollection));

        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue($streams));

        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $this->assertInstanceOf(\FFMpeg\Media\Audio::class, $ffmpeg->open(__FILE__));
    }

    public function testOpenVideo() {
        $streams = $this->getStreamCollectionMock();
        $streams->expects($this->once())
            ->method('getVideoStreams')
            ->will($this->returnValue(new StreamCollection([new Stream])));
        $streams->expects($this->never())
            ->method('getAudioStreams');

        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue($streams));

        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $this->assertInstanceOf(\FFMpeg\Media\Video::class, $ffmpeg->open(__FILE__));
    }

    /**
     * @expectedException \FFMpeg\Exception\InvalidArgumentException
     */
    public function testOpenUnknown()
    {
        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue(new StreamCollection));

        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $ffmpeg->open(__FILE__);
    }

    public function testCreateWithoutLoggerOrProbe() {
        $this->assertInstanceOf(\FFMpeg\FFMpeg::class, FFMpeg::create());
    }

    public function testCreateWithLoggerAndProbe()
    {
        $logger = $this->getLoggerMock();
        $ffprobe = $this->getFFProbeMock();

        $ffmpeg = FFMpeg::create(['timeout' => 42], $logger, $ffprobe);
        $this->assertInstanceOf('FFMpeg\FFMpeg', $ffmpeg);

        $this->assertSame($logger, $ffmpeg->getFFMpegDriver()->getProcessRunner()->getLogger());
        $this->assertSame($ffprobe, $ffmpeg->getFFProbe());
        $this->assertSame(42, $ffmpeg->getFFMpegDriver()->getProcessBuilderFactory()->getTimeout());
    }

    public function testGetSetFFProbe()
    {
        $ffprobe = $this->getFFProbeMock();
        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $this->assertSame($ffprobe, $ffmpeg->getFFProbe());
        $anotherFFProbe = $this->getFFProbeMock();
        $ffmpeg->setFFProbe($anotherFFProbe);
        $this->assertSame($anotherFFProbe, $ffmpeg->getFFProbe());
    }

    public function testGetSetDriver()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffmpeg = new FFMpeg($driver, $this->getFFProbeMock());
        $this->assertSame($driver, $ffmpeg->getFFMpegDriver());
        $anotherDriver = $this->getFFMpegDriverMock();
        $ffmpeg->setFFMpegDriver($anotherDriver);
        $this->assertSame($anotherDriver, $ffmpeg->getFFMpegDriver());
    }
}
