<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;
use Tests\FFMpeg\BaseTestCase;

abstract class FunctionalTestCase extends BaseTestCase
{
    /**
     * @param array $configuration
     *
     * @return FFMpeg
     */
    public function getFFMpeg($configuration = array())
    {
        return FFMpeg::create(array_merge(array('timeout' => 300), $configuration));
    }
}
