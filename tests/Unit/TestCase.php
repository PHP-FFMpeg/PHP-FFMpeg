<?php

namespace Tests\FFMpeg\Unit;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function assertScalar($value)
    {
        $this->assertTrue(\is_scalar($value));
    }

    public function getLoggerMock()
    {
        return $this->getMockBuilder(\Psr\Log\LoggerInterface::class)->getMock();
    }

    public function getCacheMock()
    {
        return $this->getMockBuilder(\Psr\SimpleCache\CacheInterface::class)->getMock();
    }

    public function getTimeCodeMock()
    {
        return $this->getMockBuilder(\FFMpeg\Coordinate\TimeCode::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getDimensionMock()
    {
        return $this->getMockBuilder(\FFMpeg\Coordinate\Dimension::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFramerateMock()
    {
        return $this->getMockBuilder(\FFMpeg\Coordinate\Framerate::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFrameMock()
    {
        return $this->getMockBuilder(\FFMpeg\Media\Frame::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getWaveformMock()
    {
        return $this->getMockBuilder(\FFMpeg\Media\Waveform::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFMpegDriverMock()
    {
        return $this->getMockBuilder(\FFMpeg\Driver\FFMpegDriver::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeDriverMock()
    {
        return $this->getMockBuilder(\FFMpeg\Driver\FFProbeDriver::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getStreamMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe\DataMapping\Stream::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeParserMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe\OutputParserInterface::class)->getMock();
    }

    public function getFFProbeOptionsTesterMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe\OptionsTesterInterface::class)->getMock();
    }

    public function getFFProbeMapperMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe\MapperInterface::class)->getMock();
    }

    public function getFFProbeOptionsTesterMockWithOptions(array $options)
    {
        $tester = $this->getFFProbeOptionsTesterMock();

        $tester->expects($this->any())
            ->method('has')
            ->will($this->returnCallback(function ($option) use ($options) {
                return in_array($option, $options);
            }));

        return $tester;
    }

    public function getConfigurationMock()
    {
        return $this->getMockBuilder(\Alchemy\BinaryDriver\ConfigurationInterface::class)->getMock();
    }

    public function getFormatMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe\DataMapping\Format::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getStreamCollectionMock()
    {
        return $this->getMockBuilder(\FFMpeg\FFProbe\DataMapping\StreamCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getAudioMock(string $filename = null)
    {
        $audio = $this->getMockBuilder(\FFMpeg\Media\Audio::class)
            ->disableOriginalConstructor()
            ->getMock();

        $audio->expects($this->any())
            ->method('getPathfile')
            ->will($this->returnValue($filename));

        return $audio;
    }

    protected function getVideoMock(string $filename = null)
    {
        $video = $this->getMockBuilder(\FFMpeg\Media\Video::class)
            ->disableOriginalConstructor()
            ->getMock();

        $video->expects($this->any())
            ->method('getPathfile')
            ->will($this->returnValue($filename));

        return $video;
    }

    public function getConcatMock()
    {
        return $this->getMockBuilder(\FFMpeg\Media\Concat::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFormatInterfaceMock()
    {
        $FormatInterface = $this->getMockBuilder(\FFMpeg\Format\FormatInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $FormatInterface->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));

        return $FormatInterface;
    }
}
