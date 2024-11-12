<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\Exception\InvalidArgumentException;
use Alchemy\BinaryDriver\ProcessBuilderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\ExecutableFinder;

trait AbstractProcessBuilderFactoryTrait
{
    public static $phpBinary;

    private $original;
    /**
     * @return ProcessBuilderFactory
     */
    abstract protected function getProcessBuilderFactory($binary);

    public function setUp(): void
    {
        ProcessBuilderFactory::$emulateSfLTS = null;
        if (null === static::$phpBinary) {
            $this->markTestSkipped('Unable to detect php binary, skipping');
        }
    }

    public static function setUpBeforeClass(): void
    {
        $finder            = new ExecutableFinder();
        static::$phpBinary = $finder->find('php');
    }

    public function testThatBinaryIsSetOnConstruction()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $this->assertEquals(static::$phpBinary, $factory->getBinary());
    }

    public function testGetSetBinary()
    {
        $finder  = new ExecutableFinder();
        $phpUnit = $finder->find('phpunit');

        if (null === $phpUnit) {
            $this->markTestSkipped('Unable to detect phpunit binary, skipping');
        }

        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $factory->useBinary($phpUnit);
        $this->assertEquals($phpUnit, $factory->getBinary());
    }

    public function testUseNonExistantBinary()
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $factory->useBinary('itissureitdoesnotexist');
    }

    public function testCreateShouldReturnAProcess()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $process = $factory->create();

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals("'" . static::$phpBinary . "'", $process->getCommandLine());
    }

    public function testCreateWithStringArgument()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $process = $factory->create('-v');

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals("'" . static::$phpBinary . "' '-v'", $process->getCommandLine());
    }

    public function testCreateWithArrayArgument()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $process = $factory->create(['-r', 'echo "Hello !";']);

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals("'" . static::$phpBinary . "' '-r' 'echo \"Hello !\";'", $process->getCommandLine());
    }

    public function testCreateWithTimeout()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $factory->setTimeout(200);
        $process = $factory->create(['-i']);

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals(200, $process->getTimeout());
    }
}
