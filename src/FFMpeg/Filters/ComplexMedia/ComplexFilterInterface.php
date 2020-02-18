<?php

namespace FFMpeg\Filters\ComplexMedia;

interface ComplexFilterInterface extends ComplexCompatibleFilter
{
    /**
     * @return string
     */
    public function getInLabels();

    /**
     * @return string
     */
    public function getOutLabels();
}
