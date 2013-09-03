<?php

namespace FFMpeg\Filters\Frame;

class SingleFrameFilter implements FrameFilterInterface
{
    private $priority;

    public function __construct( $priority = 12)
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
     * {@inheritdoc}
     */
    public function apply(\FFMpeg\Media\Frame $frame,
            \FFMpeg\Format\FrameInterface $format)
    {
        return array('-vframes', '1');
    }

}