<?php

namespace FFMpeg\Filters\ComplexMedia;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Media\ComplexMedia;

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
     * @param ComplexMedia $media
     *
     * @return string[] An array of arguments.
     */
    public function applyComplex(ComplexMedia $media);
}
