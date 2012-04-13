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

    /**
     * @covers FFMpeg\Binary::run
     */
    public function testRun()
    {
        BinaryTester::runner('php --version');
    }

    /**
     * @covers FFMpeg\Binary::run
     * @expectedException \RuntimeException
     */
    public function testRunFail()
    {
        BinaryTester::runner('aphp -version');
    }

}

class BinaryTester extends Binary
{

    protected static function getBinaryName()
    {
        return 'php';
    }

    public static function runner($command, $bypass_errors = false)
    {
        return self::run($command, $bypass_errors);
    }

}

class BinaryTesterWrongBinary extends Binary
{

    protected static function getBinaryName()
    {
        return '';
    }

}
