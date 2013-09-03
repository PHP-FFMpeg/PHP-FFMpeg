<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;
use FFMpeg\Filters\Audio\AudioResamplableFilter;

class AudioFilters
{
    protected $media;

    public function __construct(Audio $media)
    {
        $this->media = $media;
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
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }
    
    /**
     * Up/Down the audio volume
     *
     * @param Integer $volume
     *
     * @return AudioFilters
     */
    public function volume($volume)
    {
        $this->media->addFilter(new AudioVolumeFilter($volume));

        return $this;
    }
    
    
    /**
     * Force mono output
     *
     * @return AudioFilters
     */
    public function mono()
    {
        $this->media->addFilter(new AudioMonoFilter());

        return $this;
    }
}
