<?php

namespace FFMpeg\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getLoggerMock()
    {
        return $this->getMock('Psr\Log\LoggerInterface');
    }

    public function getCacheMock()
    {
        return $this->getMock('Doctrine\Common\Cache\Cache');
    }

    public function getFFProbeDriverMock()
    {
        return $this->getMockBuilder('FFMpeg\Driver\FFProbeDriver')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getStreamMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe\DataMapping\Stream')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeParserMock()
    {
        return $this->getMock('FFMpeg\FFProbe\OutputParserInterface');
    }

    public function getFFProbeOptionsTesterMock()
    {
        return $this->getMock('FFMpeg\FFProbe\OptionsTesterInterface');
    }

    public function getFFProbeMapperMock()
    {
        return $this->getMock('FFMpeg\FFProbe\MapperInterface');
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
        return $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');
    }

    public function getFormatMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe\DataMapping\Format')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getStreamCollectionMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe\DataMapping\StreamCollection')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
