<?php

namespace FFMpeg\Filters\AdvancedMedia;

abstract class AbstractComplexFilter implements ComplexCompatibleFilter
{
    /**
     * @var int
     */
    protected $priority;

    /**
     * AbstractComplexFilter constructor.
     *
     * @param int $priority
     */
    public function __construct($priority = 0)
    {
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion()
    {
        return '0.3';
    }

    /**
     * Generate the config of the filter.
     *
     * @param array $params Associative array of filter options. The options may be null.
     *
     * @return string The string of the form "=name1=value1:name2=value2" or empty string.
     */
    protected function buildFilterOptions(array $params)
    {
        $config = array();
        foreach ($params as $paramName => $paramValue) {
            if ($paramValue !== null) {
                $config[] = $paramName . '=' . $paramValue;
            }
        }

        if (!empty($config)) {
            return '=' . implode(':', $config);
        }

        return '';
    }
}
