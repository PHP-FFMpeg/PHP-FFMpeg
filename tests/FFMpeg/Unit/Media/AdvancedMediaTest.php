<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\AdvancedMedia;

class AdvancedMediaTest extends AbstractMediaTestCase
{
    public function testGetInputs()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $advancedMedia = new AdvancedMedia([__FILE__, __FILE__], $driver, $ffprobe);
        $this->assertSame([__FILE__, __FILE__], $advancedMedia->getInputs());
    }

    public function testGetInputsCount()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $advancedMedia = new AdvancedMedia([__FILE__, __FILE__], $driver, $ffprobe);
        $this->assertEquals(2, $advancedMedia->getInputsCount());
    }

    public function testFiltersReturnFilters()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $advancedMedia = new AdvancedMedia([__FILE__, __FILE__], $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\AdvancedMedia\ComplexFilters', $advancedMedia->filters());
    }

    public function testGetTemporaryDirectoryWithoutCustomConfiguration()
    {
        $driver        = $this->getFFMpegDriverMock();
        $ffprobe       = $this->getFFProbeMock();
        $configuration = $this->getConfigurationMock();

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->expects($this->once())
            ->method('get')
            ->with($this->equalTo('temporary_directory'))
            ->will($this->returnValue(null));

        $advancedMedia = new AdvancedMedia([__FILE__, __FILE__], $driver, $ffprobe);
        $this->assertEquals('', $advancedMedia->getTemporaryDirectory()->path());
    }

    public function testGetTemporaryDirectoryWithCustomConfiguration()
    {
        $driver        = $this->getFFMpegDriverMock();
        $ffprobe       = $this->getFFProbeMock();
        $configuration = $this->getConfigurationMock();

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->expects($this->once())
            ->method('get')
            ->with($this->equalTo('temporary_directory'))
            ->will($this->returnValue('/var/ffmpeg'));

        $advancedMedia = new AdvancedMedia([__FILE__, __FILE__], $driver, $ffprobe);
        $this->assertEquals('/var/ffmpeg', $advancedMedia->getTemporaryDirectory()->path());
    }
}
