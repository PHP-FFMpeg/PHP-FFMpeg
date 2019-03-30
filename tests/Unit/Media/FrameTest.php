<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Frame;
use FFMpeg\Coordinate\Timecode;
use FFMpeg\Exception\RuntimeException;

class FrameTest extends AbstractMediaTestCase
{
    public function testGetTimeCode()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $this->assertSame($timecode, $frame->getTimeCode());
    }

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $this->assertInstanceOf('FFMpeg\Filters\Frame\FrameFilters', $frame->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $filters = $this->getMockBuilder(\FFMpeg\Filters\FiltersCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filter = $this->getMockBuilder(\FFMpeg\Filters\Frame\FrameFilterInterface::class)->getMock();

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $frame->setFiltersCollection($filters);
        $frame->addFilter($filter);
    }

    /**
     * @dataProvider provideSaveOptions
     */
    public function testSave($accurate, $base64, $commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $timecode->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('timecode'));

        $format = $this->getFormatMock();
        $format->expects($this->once())
            ->method('get')
            ->with('duration')
            ->will($this->returnValue('42.42')); // duration large enough to test it.

        $video = $this->getVideoMock(__FILE__);

        $video->expects($this->once())
            ->method('getFormat')
            ->will($this->returnValue($format));

        $pathfile = '/target/destination';

        if (!$base64) {
            array_push($commands, $pathfile);
        }

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        if(!$base64) {
            $frame = new Frame($video, $driver, $ffprobe, $timecode);
            $this->assertSame($frame, $frame->save($pathfile, $accurate, $base64));
        }
        else {
            $frame = new Frame($video, $driver, $ffprobe, $timecode);
            $frame->save($pathfile, $accurate, $base64);
        }
    }

    public function testInvalidExtractTimecode()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Trying to save a frame that would be after the video has ended. (Extract timecode greater than the duration of the video.)'
        );

        $format = $this->getFormatMock();
        $format->expects($this->once())
            ->method('get')
            ->with('duration')
            ->will($this->returnValue('39.02'));

        $video = $this->getVideoMock(__FILE__);
        $video->expects($this->once())
            ->method('getFormat')
            ->will($this->returnValue($format));

        // Test: Are we allowed to extract a frame that exceeds the video duration?
        $timecode = Timecode::fromSeconds(42.32);

        $frame = new Frame($video, $this->getFFMpegDriverMock(), $this->getFFProbeMock(), $timecode);
        $frame->save('/path/to/stupid/file');
    }

    public function provideSaveOptions()
    {
        return array(
            array(false, false, array(
                '-y', '-ss', 'timecode',
                '-i', __FILE__,
                '-vframes', '1',
                '-f', 'image2')
            ),
            array(true, false, array(
                '-y', '-i', __FILE__,
                '-vframes', '1', '-ss', 'timecode',
                '-f', 'image2')
            ),
            array(false, true, array(
                    '-y', '-ss', 'timecode',
                    '-i', __FILE__,
                    '-vframes', '1',
                    '-f', 'image2pipe', '-')
            ),
            array(true, true, array(
                    '-y', '-i', __FILE__,
                    '-vframes', '1', '-ss', 'timecode',
                    '-f', 'image2pipe', '-')
            )
        );
    }
}
