<?php

namespace FFMpeg\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getLoggerMock()
    {
        return $this->getMockBuilder('Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
