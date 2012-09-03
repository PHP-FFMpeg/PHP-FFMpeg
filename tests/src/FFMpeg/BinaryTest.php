<?php

namespace FFMpeg;

use Monolog\Logger;
use Monolog\Handler\NullHandler;

class BinaryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Binary
     */
    protected $object;
    protected $logger;

    public function setUp()
    {
        $this->logger = new Logger('tests');
        $this->logger->pushHandler(new NullHandler());
    }

    /**
     * @covers FFMpeg\Binary::__construct
     * @expectedException \FFMpeg\Exception\BinaryNotFoundException
     */
    public function testConstruct()
    {
        $binary = new BinaryTester('pretty_binary', $this->logger);
    }

    /**
     * @covers FFMpeg\Binary::load
     */
    public function testLoad()
    {
        BinaryTester::load($this->logger);
    }

    /**
     * @covers FFMpeg\Binary::load
     * @expectedException \FFMpeg\Exception\BinaryNotFoundException
     */
    public function testLoadWrongBinary()
    {
        BinaryTesterWrongBinary::load($this->logger);
    }

}

class BinaryTester extends Binary
{

    protected static function getBinaryName()
    {
        return array('php');
    }

}

class BinaryTesterWrongBinary extends Binary
{

    protected static function getBinaryName()
    {
        return array('');
    }

}
