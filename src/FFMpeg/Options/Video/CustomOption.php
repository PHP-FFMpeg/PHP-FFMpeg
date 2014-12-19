<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FFMpeg\Options\Video;

use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class CustomOption implements VideoOptionInterface
{
    private $option;
    private $arg;
    private $priority;

    public function __construct($option, $arg, $priority = 0)
    {
        $this->option = $option;
        $this->arg = $arg;
        $this->priority = $priority;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function apply(Video $video, VideoInterface $format)
    {
        $commands = array($this->option, $this->arg);

        return $commands;
    }
}
