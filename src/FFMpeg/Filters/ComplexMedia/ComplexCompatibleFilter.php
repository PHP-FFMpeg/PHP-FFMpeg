<?php

namespace FFMpeg\Filters\ComplexMedia;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Media\ComplexMedia;

interface ComplexCompatibleFilter extends FilterInterface
{
    /**
     * Apply the complex filter to the given media.
     *
     * @param ComplexMedia $media
     *
     * @return string[] An array of arguments.
     */
    public function applyComplex(ComplexMedia $media);
}
