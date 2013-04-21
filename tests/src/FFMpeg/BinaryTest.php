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

    public function testTimeout()
    {
        $tester = BinaryTester::load($this->logger, 200);
        $this->assertEquals(200, $tester->getTimeout());
    }

    public function testDefaultTimeout()
    {
        $tester = BinaryTester::load($this->logger);
        $this->assertEquals(60, $tester->getTimeout());
    }

    public function testNoTimeout()
    {
        $tester = BinaryTester::load($this->logger, 0);
        $this->assertEquals(0, $tester->getTimeout());
    }

    public function testSetTimeout()
    {
        $tester = BinaryTester::load($this->logger);
        $tester->setTimeout(200);
        $this->assertEquals(200, $tester->getTimeout());
    }

    /**
     * @expectedException \FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidTimeout()
    {
        $tester = BinaryTester::load($this->logger);
        $tester->setTimeout(-1);
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
