<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\Point;

class PointTest extends TestCase
{
    public function testGetters()
    {
        $point = new Point(4, 25);
        $this->assertEquals(4, $point->getX());
        $this->assertEquals(25, $point->getY());
    }

    public function testDynamicPointGetters()
    {
        $point = new Point("t*100", "t", true);
        $this->assertEquals("t*100", $point->getX());
        $this->assertEquals("t", $point->getY());
    }
}
