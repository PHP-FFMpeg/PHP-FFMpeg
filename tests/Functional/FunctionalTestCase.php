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
        return FFMpeg::create(array('timeout' => 300));
    }

    /**
     * Get ffmpeg version.
     *
     * @return string
     */
    public function getFFMpegVersion()
    {
        preg_match('#version\s(\S+)#',
            $this->getFFMpeg()->getFFMpegDriver()->command('-version'), $version);
        return $version[1];
    }
}
