<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\ProcessBuilderFactory;

class LTSProcessBuilderFactoryTest extends AbstractProcessBuilderFactoryTest
{
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
