<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;
use FFMpeg\Filters\Audio\AudioResamplableFilter;

class AudioFilters
{
    private $audio;

    public function __construct(Audio $audio)
    {
        $this->audio = $audio;
    }

    /**
     * Resamples the audio file.
     *
     * @param Integer $rate
     *
     * @return AudioFilters
     */
    public function resample($rate)
    {
        $this->audio->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }
}
