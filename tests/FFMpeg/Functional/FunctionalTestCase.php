<?php

namespace FFMpeg\Functional;

use FFMpeg\FFMpeg;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    public function getFFMpeg()
    {
        return FFMpeg::create(array('timeout' => 300));
    }
}
