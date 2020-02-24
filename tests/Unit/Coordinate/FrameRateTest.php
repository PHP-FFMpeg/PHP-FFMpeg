<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\FrameRate;

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
        return array(
            array(0), array(-1.5), array(-2),
        );
    }
}
