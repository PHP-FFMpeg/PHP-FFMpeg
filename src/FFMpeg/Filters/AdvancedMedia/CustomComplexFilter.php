<?php

namespace FFMpeg\Filters\AdvancedMedia;

use FFMpeg\Media\AdvancedMedia;

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
     * Get name of the filter.
     *
     * @return string
     */
    public function getName()
    {
        return 'custom_filter';
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(AdvancedMedia $media)
    {
        return array('-filter_complex', $this->filter);
    }
}
