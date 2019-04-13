<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\Dimension;

class DimensionTest extends TestCase
{
    /**
     * @dataProvider provideInvalidDimensions
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testInvalidDimensions($width, $height)
    {
        new Dimension($width, $height);
    }

    public function provideInvalidDimensions()
    {
        return [
            [320, 0],
            [320, -10],
            [0, 240],
            [-10, 240],
            [0, 0],
            [0, -10],
            [-10, 0],
        ];
    }

    public function testGetters()
    {
        $dimension = new Dimension(320, 240);
        $this->assertEquals(320, $dimension->getWidth());
        $this->assertEquals(240, $dimension->getHeight());
    }
}
