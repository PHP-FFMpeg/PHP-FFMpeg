<?php
declare (strict_types = 1);

namespace Tests\FFMpeg\Unit\Media;

abstract class AbstractStreamableTestCase extends AbstractMediaTestCase
{
    public function testGetStreams(): void
    {
        $classname = $this->getClassName();
        $ffprobe = $this->getFFProbeMock();
        $format = $this->getFormatMock();

        $ffprobe->expects($this->once())
            ->method('format')
            ->with(__FILE__)
            ->will($this->returnValue($format));

        $media = new $classname(__FILE__, $this->getFFMpegDriverMock(), $ffprobe);
        $this->assertSame($format, $media->getFormat());
    }

    public function testGetFormat(): void
    {
        $classname = $this->getClassName();
        $ffprobe = $this->getFFProbeMock();
        $streams = $this->getStreamCollectionMock();

        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue($streams));

        $media = new $classname(__FILE__, $this->getFFMpegDriverMock(), $ffprobe);
        $this->assertSame($streams, $media->getStreams());
    }

    abstract protected function getClassName(): string;
}
