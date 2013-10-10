<?php

namespace FFMpeg\Tests\Media;

use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\AudioInterface;

abstract class AudioProg implements ProgressableInterface, AudioInterface
{
}
