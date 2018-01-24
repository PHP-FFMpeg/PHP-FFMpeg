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

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

/**
 * Clips the audio at a specific timestamp to an (optional) end
 *
 * @author     jens1o
 * @copyright  Jens Hausdorf 2018
 * @license    MIT License
 * @package    FFMpeg\Filters
 * @subpackage Audio
 */
class AudioClipFilter implements AudioFilterInterface
{

    use TPriorityFilter;

    /**
     * @var TimeCode
     */
    private $start;

    /**
     * @var TimeCode
     */
    private $duration;

    /**
     * @var int
     */
    private $priority;


    public function __construct(TimeCode $start, ?TimeCode $duration = null, int $priority = 0)
    {
        $this->start = $start;
        $this->duration = $duration;
        $this->setPriority($priority);
    }

    /**
     * Returns the start position the audio is being cutted
     *
     * @return TimeCode
     */
    public function getStart(): TimeCode
    {
        return $this->start;
    }

    /**
     * Returns how long the audio is being cutted or null when the duration is infinite.
     *
     * @return TimeCode|null
     */
    public function getDuration(): ?TimeCode
    {
        return $this->duration;
    }

    /**
     * @inheritDoc
     */
    public function apply(Audio $audio, AudioInterface $format): array
    {
        $commands = ['-ss', (string) $this->start];

        // add duration if given
        if ($this->duration !== null) {
            $commands[] = '-t';
            $commands[] = (string) $this->duration;
        }

        $commands[] = '-acodec';
        $commands[] = 'copy';

        return $commands;
    }
}
