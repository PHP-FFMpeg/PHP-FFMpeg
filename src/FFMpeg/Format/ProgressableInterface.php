<?php

namespace FFMpeg\Format;

use Evenement\EventEmitterInterface;
use FFMpeg\FFProbe;
use FFMpeg\Media\MediaTypeInterface;

interface ProgressableInterface extends EventEmitterInterface
{
    /**
     * Creates the progress listener
     *
     * @param MediaTypeInterface $media
     * @param FFProbe $ffprobe
     * @param integer $pass The current pas snumber
     * @param integer $total The total pass number
     *
     * @return array An array of listeners
     */
    public function createProgressListener(MediaTypeInterface $media, FFProbe $ffprobe, $pass, $total);
}
