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
