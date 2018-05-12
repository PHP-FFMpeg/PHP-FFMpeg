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

    public function __construct(string $artwork, string $preset = null, int $priority = 10)
    {
        $this->artwork = $artwork;
        $this->preset = $preset;
        $this->priority = $priority;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Audio $audio, AudioInterface $format): array
    {
        $commands = [ '-loop', 1, '-i', $this->artwork ];
        $presets = [ 'ultrafast', 'superfast', 'veryfast', 'faster', 'fast', 'medium', 'slow', 'slower', 'veryslow' ];

        $commands[] = '-preset';
        $commands[] = in_array($this->preset, $presets) ? $this->preset : 'veryslow';

        $commands[] = '-shortest';

        return $commands;
    }
}
