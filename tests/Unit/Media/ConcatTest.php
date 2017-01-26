<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Concat;

class ConcatTest extends AbstractMediaTestCase
{
    public function testGetSources()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $concat = new Concat(__FILE__, $driver, $ffprobe);
        $this->assertSame(__FILE__, $concat->getSources());
    }

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $concat = new Concat(__FILE__, $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\Concat\ConcatFilters', $concat->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $filter = $this->getMock('FFMpeg\Filters\Concat\ConcatFilterInterface');

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $concat = new Concat(__FILE__, $driver, $ffprobe);
        $concat->setFiltersCollection($filters);
        $concat->addFilter($filter);
    }

    /**
     * @dataProvider provideSaveFromSameCodecsOptions
     */
    public function testSaveFromSameCodecs($streamCopy, $commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $pathfile = '/target/destination';

        array_push($commands, $pathfile);

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $concat = new Concat(__FILE__, $driver, $ffprobe);
        $this->assertSame($concat, $concat->saveFromSameCodecs($pathfile, $streamCopy));
    }

    public function provideSaveFromSameCodecsOptions()
    {
        return array(
            array(
                TRUE,
                array(
                    '-f', 'concat',
                    '-safe', '0',
                    '-i', __FILE__,
                    '-c', 'copy'
                ),
            ),
            array(
                FALSE,
                array(
                    '-f', 'concat',
                    '-safe', '0',
                    '-i', __FILE__
                )
            ),
        );
    }

    /**
     * @dataProvider provideSaveFromDifferentCodecsOptions
     */
    public function testSaveFromDifferentCodecs($commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $format = $this->getFormatInterfaceMock();

        $pathfile = '/target/destination';

        array_push($commands, $pathfile);

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $concat = new Concat(__DIR__ . '/../../files/concat-list.txt', $driver, $ffprobe);
        $this->assertSame($concat, $concat->saveFromDifferentCodecs($format, $pathfile));
    }

    public function provideSaveFromDifferentCodecsOptions()
    {
        return array(
            array(
                array(
                    '-i', './concat-1.mp4',
                    '-i', 'concat-2.mp4',
                    '-filter_complex', 
                    '[0:v:0] [0:a:0] [1:v:0] [1:a:0] concat=n=2:v=1:a=1 [v] [a]',
                    '-map', '[v]', 
                    '-map', '[a]'
                ),
            ),
        );
    }
}
