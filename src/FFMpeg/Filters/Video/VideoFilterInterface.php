<?php

namespace FFMpeg\Filters\Video;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

interface VideoFilterInterface extends FilterInterface
{
    public function apply(Video $video, VideoInterface $format);
}
