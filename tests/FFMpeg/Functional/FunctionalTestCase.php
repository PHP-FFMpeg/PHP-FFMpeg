<?php

namespace FFMpeg\Functional;

date_default_timezone_set('UTC');

use FFMpeg\FFMpeg;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return FFMpeg
     */
    public function getFFMpeg()
    {
        return FFMpeg::create(array('timeout' => 300));
    }
}
