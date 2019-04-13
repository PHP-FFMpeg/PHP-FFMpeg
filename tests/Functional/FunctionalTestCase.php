<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;
use PHPUnit\Framework\TestCase;

abstract class FunctionalTestCase extends TestCase
{
    /**
     * @return FFMpeg
     */
    public function getFFMpeg()
    {
        return FFMpeg::create(['timeout' => 300]);
    }
}
