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
}
