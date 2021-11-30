<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\ProgressableInterface;

abstract class AudioProg implements ProgressableInterface, AudioInterface
{
}
