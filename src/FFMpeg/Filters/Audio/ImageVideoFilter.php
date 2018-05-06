<?php

namespace FFMpeg\Filters\Audio;


use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

class ImageVideoFilter implements AudioFilterInterface
{
    use TPriorityFilter;

    /**
     * @var string
     */
    private $artwork;
    /**
     * @var string
     */
    private $preset;
    /**
     * @var int
     */
    private $priority;
    /**
     * @var array
     */
    private $extraFlags;

    public function __construct($artwork, $preset = null, $flags = null, $priority = 10)
    {
        $this->artwork = $artwork;
        $this->extraFlags = $flags;
        $this->preset = $preset;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Audio $audio, AudioInterface $format)
    {
        $commands = array('-loop', 1, '-i', (string)$this->artwork);

        if (!is_null($this->extraFlags)) {
            $commands = array_merge($commands, $this->extraFlags);
        }

        $commands[] = '-preset';
        $commands[] = (!is_null($this->preset)) ? (string)$this->preset : 'veryslow';

        $commands[] = '-shortest';

        return $commands;
    }
}