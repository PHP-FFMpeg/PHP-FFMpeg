<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;

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
     * Clips (cuts) the audio file.
     *
     * @param TimeCode $start
     * @param TimeCode $duration
     *
     * @return AudioFilters
     */
    public function clip($start, $duration = null)
    {
        $this->media->addFilter(new ClipFilter($start, $duration));

        return $this;
    }

}
