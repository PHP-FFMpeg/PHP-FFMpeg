<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use FFMpeg\Coordinate\FrameRate;
use Tests\FFMpeg\Unit\TestCase;

class FrameRateTest extends TestCase
{
    public function testGetter()
    {
        $fr = new FrameRate(23.997);
        $this->assertEquals(23.997, $fr->getValue());
    }

    /**
     * @dataProvider provideInvalidFrameRates
     */
    public function testInvalidFrameRate($value)
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        new FrameRate($value);
    }

    public function provideInvalidFrameRates()
    {
        return [
            [0], [-1.5], [-2],
        ];
    }
}
