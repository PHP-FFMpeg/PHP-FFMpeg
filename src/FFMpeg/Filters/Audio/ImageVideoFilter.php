<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

/**
 * Converts the audio to video and `artwork` as video content.
 *
 * @author     pralhad.shrestha05@gmail.com
 * @copyright  Jens Hausdorf 2018
 * @license    MIT License
 * @package    FFMpeg\Filters
 * @subpackage Audio
 */
class ImageVideoFilter implements AudioFilterInterface
{
    use TPriorityFilter;

    /**
     * @var string
     */
    private $artwork;
    /**
     * @var string
     */
    private $preset;
    /**
     * @var int
     */
    private $priority;
    /**
     * @var array
     */
    private $extraFlags;

    public function __construct(string $artwork, string $preset = null, array $flags = null, int $priority = 10)
    {
        $this->artwork = $artwork;
        $this->extraFlags = $flags;
        $this->preset = $preset;
        $this->priority = $priority;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Audio $audio, AudioInterface $format): array
    {
        $commands = array('-loop', 1, '-i', $this->artwork);

        if (!is_null($this->extraFlags)) {
            $commands = array_merge($commands, $this->extraFlags);
        }

        $commands[] = '-preset';
        $commands[] = (!is_null($this->preset)) ? $this->preset : 'veryslow';

        $commands[] = '-shortest';

        return $commands;
    }
}
