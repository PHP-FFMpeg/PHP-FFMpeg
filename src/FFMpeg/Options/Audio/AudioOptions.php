<?php

namespace FFMpeg\Options\Audio;

use FFMpeg\Media\Audio;

class AudioOptions
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
     * @return AudioOptions
     */
    public function resample($rate)
    {
        $this->media->addOption(new AudioResamplableOption($rate));

        return $this;
    }
}
