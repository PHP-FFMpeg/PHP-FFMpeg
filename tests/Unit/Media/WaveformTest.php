<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Waveform;

class WaveformTest extends AbstractMediaTestCase
{

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $waveform = new Waveform($this->getVideoMock(__FILE__), $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\Waveform\WaveformFilters', $waveform->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $filter = $this->getMock('FFMpeg\Filters\Waveform\WaveformFilterInterface');

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $waveform = new Waveform($this->getVideoMock(__FILE__), $driver, $ffprobe);
        $waveform->setFiltersCollection($filters);
        $waveform->addFilter($filter);
    }

    /**
     * @dataProvider provideSaveOptions
     */
    public function testSave($commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $pathfile = '/target/destination';

        array_push($commands, $pathfile);

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $waveform = new Waveform($this->getVideoMock(__FILE__), $driver, $ffprobe);
        $this->assertSame($waveform, $waveform->save($pathfile));
    }

    public function provideSaveOptions()
    {
        return array(
            array(
                array(
                    '-i', 'input', '-filter_complex',
                    '-frames:v', '1',
                    __FILE__
                ),
            ),
        );
    }
}
