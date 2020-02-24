<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;
use Tests\FFMpeg\BaseTestCase;

abstract class FunctionalTestCase extends BaseTestCase
{
    /**
     * @return FFMpeg
     */
    public function getFFMpeg()
    {
        return FFMpeg::create(array('timeout' => 300));
    }
}
