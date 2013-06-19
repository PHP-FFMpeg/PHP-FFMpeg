<?php

namespace FFMpeg\Format;

use Evenement\EventEmitterInterface;
use FFMpeg\FFProbe;

interface ProgressableInterface extends EventEmitterInterface
{
    public function createProgressListener(FFProbe $ffprobe, $pathfile);
}
