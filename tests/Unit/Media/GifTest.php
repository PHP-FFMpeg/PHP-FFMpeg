<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Gif;
use FFMpeg\Coordinate\Dimension;

class GifTest extends AbstractMediaTestCase
{
    public function testGetTimeCode()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $dimension = $this->getDimensionMock();

        $gif = new Gif($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode, $dimension);
        $this->assertSame($timecode, $gif->getTimeCode());
    }

    public function testGetDimension()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $dimension = $this->getDimensionMock();

        $gif = new Gif($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode, $dimension);
        $this->assertSame($dimension, $gif->getDimension());
    }

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $dimension = $this->getDimensionMock();

        $gif = new Gif($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode, $dimension);
        $this->assertInstanceOf('FFMpeg\Filters\Gif\GifFilters', $gif->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $dimension = $this->getDimensionMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $filter = $this->getMockBuilder('FFMpeg\Filters\Gif\GifFilterInterface')->getMock();

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $gif = new Gif($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode, $dimension);
        $gif->setFiltersCollection($filters);
        $gif->addFilter($filter);
    }

    /**
     * @dataProvider provideSaveOptions
     */
    public function testSave($dimension, $duration, $commands)
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

        $gif = new Gif($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode, $dimension, $duration);
        $this->assertSame($gif, $gif->save($pathfile));
    }

    public function provideSaveOptions()
    {
        return array(
            array(
                new Dimension(320, 240), 3,
                array(
                    '-ss', 'timecode',
                    '-t', '3',
                    '-i', __FILE__,
                    '-vf',
                    'scale=320:-1', '-gifflags',
                    '+transdiff', '-y'
                ),
            ),
            array(
                new Dimension(320, 240), null,
                array(
                    '-ss', 'timecode',
                    '-i', __FILE__,
                    '-vf',
                    'scale=320:-1', '-gifflags',
                    '+transdiff', '-y'
                )
            ),
        );
    }
}
