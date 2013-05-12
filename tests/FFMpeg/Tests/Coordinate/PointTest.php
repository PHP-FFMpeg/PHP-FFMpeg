<?php

namespace FFMpeg\Tests\Coordinate;

use FFMpeg\Tests\TestCase;
use FFMpeg\Coordinate\Point;

class PointTest extends TestCase
{
    public function testGetters()
    {
        $point = new Point(4, 25);
        $this->assertEquals(4, $point->getX());
        $this->assertEquals(25, $point->getY());
    }
}
