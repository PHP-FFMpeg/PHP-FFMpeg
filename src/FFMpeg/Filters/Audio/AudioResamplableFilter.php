<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

class AudioResamplableFilter implements AudioFilterInterface
{
    private $rate;

    public function __construct($rate)
    {
        $this->rate = $rate;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function apply(Audio $audio, AudioInterface $format)
    {
        return array('-ac', 2, '-ar', $this->rate);
    }
}
