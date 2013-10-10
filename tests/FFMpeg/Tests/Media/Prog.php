<?php

namespace FFMpeg\Tests\Media;

use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\VideoInterface;

abstract class Prog implements ProgressableInterface, VideoInterface
{
}
