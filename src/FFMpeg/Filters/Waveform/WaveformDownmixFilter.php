<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Waveform;

use FFMpeg\Exception\RuntimeException;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Media\Waveform;

class WaveformDownmixFilter implements WaveformFilterInterface
{

    use TPriorityFilter;

    /**
     * @var bool
     */
    private $downmix;

    /**
     * @var int
     */
    private $priority;

    // By default, the downmix value is set to FALSE.
    public function __construct(bool $downmix = false, int $priority = 0)
    {
        $this->downmix = $downmix;
        $this->setPriority($priority);
    }

    /**
     * @inheritDoc
     */
    public function getDownmix(): bool 
    {
        return $this->downmix;
    }

    /**
     * @inheritDoc
     */
    public function apply(Waveform $waveform): array 
    {
        $commands = [];

        // If the downmix parameter is set to true, we add an option to the FFMPEG command
        if($this->downmix) {
            foreach ($waveform->getAudio()->getStreams()->audios() as $stream) {
                $commands[] = '"aformat=channel_layouts=mono"';
            }
        }

        return $commands;
    }
}
