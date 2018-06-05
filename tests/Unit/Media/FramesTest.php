<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Media\Frames;

class FramesTest extends AbstractMediaTestCase
{
    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $frames = new Frames($this->getVideoMock(__FILE__), $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\Frames\FramesFilters', $frames->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $filter = $this->getMockBuilder('FFMpeg\Filters\Frames\FramesFilterInterface')->getMock();

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $frames = new Frames($this->getVideoMock(__FILE__), $driver, $ffprobe);
        $frames->setFiltersCollection($filters);
        $frames->addFilter($filter);
    }

    /**
     * @dataProvider provideSaveOptions
     */
    public function testSave($destination, $framerate, $duration, $fileType, $commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $streams = new StreamCollection(array(
            new Stream(array(
                'codec_type' => 'video',
                'duration' => $duration,
            ))
        ));

        $video = $this->getVideoMock(__FILE__);
        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $frames = new Frames($video, $driver, $ffprobe, $framerate);
        $frames->setFrameFileType($fileType);

        $this->assertSame($frames, $frames->save($destination));
    }

    public function provideSaveOptions()
    {
        return array(
            array(
                '/some/destination',
                Frames::FRAMERATE_EVERY_SEC,
                100,
                'jpg',
                array(
                    '-y',
                    '-i', __FILE__,
                    '-vf',
                    'fps=' . Frames::FRAMERATE_EVERY_SEC,
                    '/some/destination/frame-%03d.jpg'
                ),
            ),
            array(
                '/some/destination/',
                Frames::FRAMERATE_EVERY_60SEC,
                100,
                'png',
                array(
                    '-y',
                    '-i', __FILE__,
                    '-vf',
                    'fps=' . Frames::FRAMERATE_EVERY_60SEC,
                    '/some/destination/frame-%02d.png'
                ),
            ),
        );
    }
}
