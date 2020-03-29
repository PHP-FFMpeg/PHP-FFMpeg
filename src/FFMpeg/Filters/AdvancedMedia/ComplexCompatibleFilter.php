<?php

namespace FFMpeg\Filters\AdvancedMedia;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Media\AdvancedMedia;

/**
 * A filter that can be used inside "-filter_complex" option.
 */
interface ComplexCompatibleFilter extends FilterInterface
{
    /**
     * Get name of the filter.
     *
     * @return string
     */
    public function getName();

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion();

    /**
     * Apply the complex filter to the given media.
     *
     * @param AdvancedMedia $media
     *
     * @return string[] An array of arguments.
     */
    public function applyComplex(AdvancedMedia $media);
}
