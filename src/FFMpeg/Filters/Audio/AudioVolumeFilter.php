<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;
use FFMpeg\Format\AudioInterface;

class AudioVolumeFilter implements AudioFilterInterface
{
    private $volume;
    private $priority;

    public function __construct($volume = '256', $priority = 0)
    {
        $this->volume = $volume;
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
        return array('-vol',$this->volume);
    }
}
