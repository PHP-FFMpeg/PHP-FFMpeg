<?php
declare (strict_types = 1);

namespace Tests\FFMpeg\Unit;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{

    public function assertScalar($value)
    {
        $this->assertTrue(is_scalar($value));
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
        $mock = $this->getMockBuilder(\Alchemy\BinaryDriver\ConfigurationInterface::class)
            ->getMock();

        // return default number of threads
        $mock
            ->expects($this->any())
            ->method('get')
            ->with('ffmpeg.threads')
            ->will($this->returnValue('2'));

        return $mock;
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

    protected function getAudioMock()
    {
        $audio = $this->getMockBuilder(\FFMpeg\Media\Audio::class)
            ->disableOriginalConstructor()
            ->getMock();

        $audio->expects($this->any())
            ->method('addFilter')
            ->will($this->returnValue($audio));

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

        $video->expects($this->any())
            ->method('addFilter')
            ->will($this->returnValue($video));

        return $video;
    }

    public function getConcatMock()
    {
        return $this->getMockBuilder(\FFMpeg\Media\Concat::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getVideoInterfaceMock()
    {
        $videoInterface = $this->getMockBuilder(\FFMpeg\Format\VideoInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $videoInterface->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));

        $videoInterface->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));

        return $videoInterface;
    }

    public function getFormatInterfaceMock()
    {
        $formatInterface = $this->getMockBuilder(\FFMpeg\Format\FormatInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $formatInterface->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));

        return $formatInterface;
    }
}
