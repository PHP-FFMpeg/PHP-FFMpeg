<?php

namespace FFMpeg\Filters\Frame;

class QualityFrameFilter implements FrameFilterInterface
{
    private $priority;
    private $quality;

    public function __construct($quality = '10', $priority = 12)
    {
        $this->priority = $priority;
        $this->quality = $quality;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(\FFMpeg\Media\Frame $frame,
            \FFMpeg\Format\FrameInterface $format)
    {
        return array('-qscale',$this->quality);
    }

}