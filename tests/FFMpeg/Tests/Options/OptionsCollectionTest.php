<?php

namespace FFMpeg\Tests\Options;

use FFMpeg\Options\OptionsCollection;
use FFMpeg\Options\Audio\SimpleOption;
use FFMpeg\Tests\TestCase;

class OptionsCollectionTest extends TestCase
{
    public function testCount()
    {
        $coll = new OptionsCollection();
        $this->assertCount(0, $coll);

        $coll->add($this->getMock('FFMpeg\Options\OptionInterface'));
        $this->assertCount(1, $coll);

        $coll->add($this->getMock('FFMpeg\Options\OptionInterface'));
        $this->assertCount(2, $coll);
    }

    public function testIterator()
    {
        $coll = new OptionsCollection();
        $coll->add($this->getMock('FFMpeg\Options\OptionInterface'));
        $coll->add($this->getMock('FFMpeg\Options\OptionInterface'));

        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
        $this->assertCount(2, $coll->getIterator());
    }

    public function testEmptyIterator()
    {
        $coll = new OptionsCollection();
        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
    }

    public function testIteratorSort()
    {
        $coll = new OptionsCollection();
        $coll->add(new SimpleOption(array('a')));
        $coll->add(new SimpleOption(array('1'), 12));
        $coll->add(new SimpleOption(array('b')));
        $coll->add(new SimpleOption(array('2'), 12));
        $coll->add(new SimpleOption(array('c')));
        $coll->add(new SimpleOption(array('3'), 10));
        $coll->add(new SimpleOption(array('d')));
        $coll->add(new SimpleOption(array('4'), -2));
        $coll->add(new SimpleOption(array('e')));

        $data = array();
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\AudioInterface');

        foreach ($coll as $option) {
            $data = array_merge($data, $option->apply($video, $format));
        }

        $this->assertEquals(array('1', '2', '3', 'a', 'b', 'c', 'd', 'e', '4'), $data);
    }
}
