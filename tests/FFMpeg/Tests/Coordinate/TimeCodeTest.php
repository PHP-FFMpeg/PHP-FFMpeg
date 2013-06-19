<?php

namespace FFMpeg\Tests\Coordinate;

use FFMpeg\Tests\TestCase;
use FFMpeg\Coordinate\TimeCode;

class TimeCodeTest extends TestCase
{
    /**
     * @dataProvider provideTimecodes
     */
    public function testFromString($timecode, $expected)
    {
        $tc = TimeCode::fromString($timecode);
        $this->assertEquals((string) $tc, $expected);
    }

    public function provideTimeCodes()
    {
        return array(
            array('1:02:04:05:20', '26:04:05.20'),
            array('1:02:04:05.20', '26:04:05.20'),
            array('02:04:05:20', '02:04:05.20'),
            array('02:04:05.20', '02:04:05.20'),
            array('00:00:05.20', '00:00:05.20'),
            array('00:00:00.00', '00:00:00.00'),
        );
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testFromInvalidString()
    {
        TimeCode::fromString('lalali lala');
    }
}
