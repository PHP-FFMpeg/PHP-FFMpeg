<?php

namespace FFMpeg\Filters\ComplexMedia;

use FFMpeg\Media\ComplexMedia;

class CustomComplexFilter extends AbstractComplexFilter
{
    /**
     * @var string
     */
    private $filter;

    /**
     * CustomComplexFilter constructor.
     *
     * @param string $filter
     * @param int    $priority
     */
    public function __construct($filter, $priority = 0)
    {
        parent::__construct($priority);
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(ComplexMedia $media)
    {
        return array('-filter_complex', $this->filter);
    }
}
