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

    public function resample($rate)
    {
        return $this->audio->addFilter(new AudioResamplableFilter($rate));
    }
}
