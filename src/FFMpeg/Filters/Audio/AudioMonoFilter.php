<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;
use FFMpeg\Format\AudioInterface;

class AudioMonoFilter implements AudioFilterInterface
{
    private $priority;

    public function __construct( $priority = 0)
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
    public function apply(Audio $audio, AudioInterface $format)
    {
        return array('-ac',1);
    }
}
