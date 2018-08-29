<?php
declare (strict_types = 1);

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
     * @var int
     */
    private $priority;

    public function __construct(int $priority = 0)
    {
        $this->setPriority($priority);
    }

    /**
     * @inheritDoc
     */
    public function apply(Waveform $waveform) : array
    {
        $commands = [];

        foreach ($waveform->getAudio()->getStreams()->getAudioStreams() as $stream) {
            $commands[] = '"aformat=channel_layouts=mono"';
        }

        return $commands;
    }
}
