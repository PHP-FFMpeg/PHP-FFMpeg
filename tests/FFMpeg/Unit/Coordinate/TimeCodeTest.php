<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use FFMpeg\Coordinate\TimeCode;
use Tests\FFMpeg\Unit\TestCase;

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
        return [
            ['1:02:04:05:20', '26:04:05.20'],
            ['1:02:04:05.20', '26:04:05.20'],
            ['02:04:05:20', '02:04:05.20'],
            ['02:04:05.20', '02:04:05.20'],
            ['00:00:05.20', '00:00:05.20'],
            ['00:00:00.00', '00:00:00.00'],
        ];
    }

    public function testFromInvalidString()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        TimeCode::fromString('lalali lala');
    }

    /**
     * @dataProvider provideSeconds
     */
    public function testFromSeconds($seconds, $expected)
    {
        $tc = TimeCode::fromSeconds($seconds);
        $this->assertEquals($expected, (string) $tc);
    }

    public function provideSeconds()
    {
        return [
            [0.467,  '00:00:00.47'],
            [12.467, '00:00:12.47'],
            [59.867, '00:00:59.87'],
            [72.467, '00:01:12.47'],
            [3599.467, '00:59:59.47'],
            [3600.467, '01:00:00.47'],
            [86422.467, '24:00:22.47'],
        ];
    }
}
