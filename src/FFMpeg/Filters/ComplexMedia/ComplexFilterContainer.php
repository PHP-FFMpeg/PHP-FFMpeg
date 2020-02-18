<?php

namespace FFMpeg\Filters\ComplexMedia;

use FFMpeg\Media\ComplexMedia;

/**
 * Container for the complex filter.
 */
class ComplexFilterContainer implements ComplexFilterInterface
{
    /**
     * @var int
     */
    private $priority;

    /**
     * @var ComplexCompatibleFilter
     */
    private $baseFilter;

    /**
     * @var string
     */
    private $inLabels;

    /**
     * @var string
     */
    private $outLabels;

    /**
     * ComplexFilter constructor.
     *
     * @param string                  $inLabels
     * @param ComplexCompatibleFilter $baseFilter
     * @param string                  $outLabels
     */
    public function __construct($inLabels, ComplexCompatibleFilter $baseFilter, $outLabels)
    {
        $this->priority = $baseFilter->getPriority();
        $this->inLabels = $inLabels;
        $this->baseFilter = $baseFilter;
        $this->outLabels = $outLabels;
    }

    /**
     * Returns the priority of the filter.
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getInLabels()
    {
        return $this->inLabels;
    }

    /**
     * @return string
     */
    public function getOutLabels()
    {
        return $this->outLabels;
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(ComplexMedia $media)
    {
        return $this->baseFilter->applyComplex($media);
    }
}
