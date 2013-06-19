<?php

namespace FFMpeg\Tests\FFProbe\DataMapping;

use FFMpeg\Tests\TestCase;
use FFMpeg\FFProbe\DataMapping\AbstractData;

class AbstractDataTest extends TestCase
{
    public function testHas()
    {
        $imp = new Implementation(array('key1' => 'value1', 'key2' => 'value2'));

        $this->assertTrue($imp->has('key1'));
        $this->assertTrue($imp->has('key2'));
        $this->assertFalse($imp->has('value1'));
        $this->assertFalse($imp->has('key3'));
    }

    public function testGet()
    {
        $imp = new Implementation(array('key1' => 'value1', 'key2' => 'value2'));

        $this->assertEquals('value1', $imp->get('key1'));
        $this->assertEquals('value2', $imp->get('key2'));
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testGetInvalid()
    {
        $imp = new Implementation(array('key1' => 'value1', 'key2' => 'value2'));

        $imp->get('key3');
    }

    public function testKeys()
    {
        $imp = new Implementation(array('key1' => 'value1', 'key2' => 'value2'));

        $this->assertEquals(array('key1', 'key2'), $imp->keys());
    }

    public function testAll()
    {
        $values = array('key1' => 'value1', 'key2' => 'value2');
        $imp = new Implementation($values);

        $this->assertEquals($values, $imp->all());
    }
}

class Implementation extends AbstractData
{
}
