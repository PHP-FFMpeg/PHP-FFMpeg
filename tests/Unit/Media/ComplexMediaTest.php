<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\ComplexMedia;

class ComplexMediaTest extends AbstractMediaTestCase
{
    public function testGetInputs()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $complexMedia = new ComplexMedia(array(__FILE__, __FILE__), $driver, $ffprobe);
        $this->assertSame(array(__FILE__, __FILE__), $complexMedia->getInputs());
    }

    public function testGetInputsCount()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $complexMedia = new ComplexMedia(array(__FILE__, __FILE__), $driver, $ffprobe);
        $this->assertEquals(2, $complexMedia->getInputsCount());
    }

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $complexMedia = new ComplexMedia(array(__FILE__, __FILE__), $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\ComplexMedia\ComplexFilters', $complexMedia->filters());
    }
}
