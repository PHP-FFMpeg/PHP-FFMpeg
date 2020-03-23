<?php

namespace FFMpeg\Filters\AdvancedMedia;

/**
 * A filter that is completely ready to use inside "-filter_complex" option.
 */
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
