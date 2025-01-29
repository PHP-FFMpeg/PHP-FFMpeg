<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\ProcessBuilderFactory;
use Alchemy\Tests\BinaryDriver\AbstractProcessBuilderFactoryTrait;
use Tests\FFMpeg\Unit\TestCase;

class LTSProcessBuilderFactoryTest extends TestCase
{
    use AbstractProcessBuilderFactoryTrait;

    public function setUp(): void
    {
        if (!class_exists('Symfony\Component\Process\ProcessBuilder')) {
            $this->markTestSkipped('ProcessBuilder is not available.');
            return;
        }

        parent::setUp();
    }

    protected function getProcessBuilderFactory($binary)
    {
        $factory = new ProcessBuilderFactory($binary);
        $factory->setBuilder(new LTSProcessBuilder());
        ProcessBuilderFactory::$emulateSfLTS = false;
        $factory->useBinary($binary);

        return $factory;
    }
}
