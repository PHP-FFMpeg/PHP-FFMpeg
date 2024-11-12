<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\ProcessBuilderFactory;
use Alchemy\Tests\BinaryDriver\AbstractProcessBuilderFactoryTrait;
use Tests\FFMpeg\Unit\TestCase;

class NONLTSProcessBuilderFactoryTest extends TestCase
{
    use AbstractProcessBuilderFactoryTrait;

    protected function getProcessBuilderFactory($binary)
    {
        ProcessBuilderFactory::$emulateSfLTS = true;

        return new ProcessBuilderFactory($binary);
    }
}
