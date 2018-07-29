<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\TimeCode;

class TimeCodeTest extends TestCase
{
    /**
     * @dataProvider provideTimecodes
     */
    public function testFromString($timecode, $expected)
    {
        $tc = TimeCode::fromString($timecode);
        $this->assertEquals((string)$tc, $expected);
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

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testFromInvalidString()
    {
        TimeCode::fromString('lalali lala');
    }

    /**
     * @dataProvider provideSeconds
     */
    public function testFromSeconds($seconds, $expected)
    {
        $tc = TimeCode::fromSeconds($seconds);
        $this->assertEquals($expected, (string)$tc);
    }

    /**
     * @dataProvider provideRoundedSeconds
     */
    public function testToSeconds($seconds)
    {
        // tests whether `fromSeconds` and `toSeconds` are the same
        $tc = TimeCode::fromSeconds($seconds);

        $this->assertEquals($tc->toSeconds(), $seconds);
    }

    public function provideSeconds()
    {
        return [
            [0.467, '00:00:00.47'],
            [12.467, '00:00:12.47'],
            [59.867, '00:00:59.87'],
            [72.467, '00:01:12.47'],
            [3599.467, '00:59:59.47'],
            [3600.467, '01:00:00.47'],
            [86422.467, '24:00:22.47'],
        ];
    }

    public function provideRoundedSeconds()
    {
        return [
            [0.467],
            [12.467],
            [60.124],
            [72.467],
            [3599.467],
            [3600.467],
            [86422.467],
        ];
    }
}
