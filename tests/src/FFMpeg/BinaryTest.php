<?php

namespace FFMpeg;

class BinaryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Binary
     */
    protected $object;

    /**
     * @covers FFMpeg\Binary::__construct
     */
    public function testConstruct()
    {
        $binary = new BinaryTester('pretty_binary');
        $binary = new BinaryTester('pretty_binary', new \Monolog\Logger('test'));
    }

    /**
     * @covers FFMpeg\Binary::load
     */
    public function testLoad()
    {
        BinaryTester::load();
    }

    /**
     * @covers FFMpeg\Binary::load
     * @expectedException \FFMpeg\Exception\BinaryNotFoundException
     */
    public function testLoadWrongBinary()
    {
        BinaryTesterWrongBinary::load();
    }

}

class BinaryTester extends Binary
{

    protected static function getBinaryName()
    {
        return 'php';
    }

}

class BinaryTesterWrongBinary extends Binary
{

    protected static function getBinaryName()
    {
        return '';
    }

}
