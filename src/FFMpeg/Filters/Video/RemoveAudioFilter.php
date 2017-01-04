<?php

namespace FFMpeg\Filters\Video;

use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class RemoveAudioFilter implements VideoFilterInterface
{

    /** @var integer */
    private $priority;

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
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $commands = array('-an');
        return $commands;
    }
}
