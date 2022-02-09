<?php

namespace Tests\FFMpeg\Unit\Filters;

use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Filters\FiltersCollection;
use Tests\FFMpeg\Unit\TestCase;

class FiltersCollectionTest extends TestCase
{
    public function testCount()
    {
        $coll = new FiltersCollection();
        $this->assertCount(0, $coll);

        $coll->add($this->getMockBuilder('FFMpeg\Filters\FilterInterface')->getMock());
        $this->assertCount(1, $coll);

        $coll->add($this->getMockBuilder('FFMpeg\Filters\FilterInterface')->getMock());
        $this->assertCount(2, $coll);
    }

    public function testIterator()
    {
        $coll = new FiltersCollection();
        $coll->add($this->getMockBuilder('FFMpeg\Filters\FilterInterface')->getMock());
        $coll->add($this->getMockBuilder('FFMpeg\Filters\FilterInterface')->getMock());

        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
        $this->assertCount(2, $coll->getIterator());
    }

    public function testEmptyIterator()
    {
        $coll = new FiltersCollection();
        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
    }

    public function testIteratorSort()
    {
        $coll = new FiltersCollection();
        $coll->add(new SimpleFilter(['a']));
        $coll->add(new SimpleFilter(['1'], 12));
        $coll->add(new SimpleFilter(['b']));
        $coll->add(new SimpleFilter(['2'], 12));
        $coll->add(new SimpleFilter(['c']));
        $coll->add(new SimpleFilter(['3'], 10));
        $coll->add(new SimpleFilter(['d']));
        $coll->add(new SimpleFilter(['4'], -2));
        $coll->add(new SimpleFilter(['e']));

        $data = [];
        $video = $this->getVideoMock();
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        foreach ($coll as $filter) {
            $data = array_merge($data, $filter->apply($video, $format));
        }

        $this->assertEquals(['1', '2', '3', 'a', 'b', 'c', 'd', 'e', '4'], $data);
    }
}
