<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\AdvancedMedia;

class AdvancedMediaTest extends AbstractMediaTestCase
{
    public function testGetInputs()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $advancedMedia = new AdvancedMedia(array(__FILE__, __FILE__), $driver, $ffprobe);
        $this->assertSame(array(__FILE__, __FILE__), $advancedMedia->getInputs());
    }

    public function testGetInputsCount()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $advancedMedia = new AdvancedMedia(array(__FILE__, __FILE__), $driver, $ffprobe);
        $this->assertEquals(2, $advancedMedia->getInputsCount());
    }

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $advancedMedia = new AdvancedMedia(array(__FILE__, __FILE__), $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\AdvancedMedia\ComplexFilters', $advancedMedia->filters());
    }
}
