<?php

namespace FFMpeg\Tests\Media;

use FFMpeg\Media\Frame;
use FFMpeg\Tests\TestCase;

class FrameTest extends TestCase
{
    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testFrameWithInvalidFile()
    {
        new Frame('/No/file', $this->getFFMpegDriverMock(), $this->getFFProbeMock(), $this->getTimeCodeMock());
    }

    public function testGetTimeCode()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $frame = new Frame(__FILE__, $driver, $ffprobe, $timecode);
        $this->assertSame($timecode, $frame->getTimeCode());
    }

    /**
     * @dataProvider provideSaveAsOptions
     */
    public function testSaveAs($accurate, $commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $timecode->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('timecode'));

        $pathfile = '/target/destination';

        array_push($commands, $pathfile);

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $frame = new Frame(__FILE__, $driver, $ffprobe, $timecode);
        $this->assertSame($frame, $frame->saveAs($pathfile, $accurate));
    }

    public function provideSaveAsOptions()
    {
        return array(
            array(false, array(
                '-ss', 'timecode',
                '-i', __FILE__,
                '-vframes', '1',
                '-f', 'image2')
            ),
            array(true, array(
                '-i', __FILE__,
                '-vframes', '1', '-ss', 'timecode',
                '-f', 'image2'
            )),
        );
    }
}
