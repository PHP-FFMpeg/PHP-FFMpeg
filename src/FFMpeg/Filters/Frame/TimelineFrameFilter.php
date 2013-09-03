<?php

namespace FFMpeg\Filters\Frame;

class TimelineFrameFilter implements FrameFilterInterface
{
    private $priority;
    private $fps;
    
    public function __construct($fps = '1/60', $priority = 12)
    {
        $this->priority = $priority;
        $this->fps = $fps;
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
        return array('-vf','fps=fps='.$this->fps.',scale=\'110:-1\'');
    }

}