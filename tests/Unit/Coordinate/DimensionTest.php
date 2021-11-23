<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use FFMpeg\Coordinate\Dimension;
use Tests\FFMpeg\Unit\TestCase;

class DimensionTest extends TestCase
{
    /**
     * @dataProvider provideInvalidDimensions
     */
    public function testInvalidDimensions($width, $height)
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
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
