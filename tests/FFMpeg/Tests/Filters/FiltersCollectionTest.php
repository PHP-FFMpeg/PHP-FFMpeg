<?php

namespace FFMpeg\Tests\Filters;

use FFMpeg\Filters\FiltersCollection;

class FiltersCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testCount()
    {
        $coll = new FiltersCollection();
        $this->assertCount(0, $coll);

        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));
        $this->assertCount(1, $coll);

        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));
        $this->assertCount(2, $coll);
    }

    public function testIterator()
    {
        $coll = new FiltersCollection();
        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));
        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));

        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
        $this->assertCount(2, $coll->getIterator());
    }
}
