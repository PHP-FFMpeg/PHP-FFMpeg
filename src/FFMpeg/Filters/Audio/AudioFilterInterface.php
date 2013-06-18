<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

interface AudioFilterInterface extends FilterInterface
{
    public function apply(Audio $audio, AudioInterface $format);
}
