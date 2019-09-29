<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Waveform;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Media\Waveform;

class WaveformDownmixFilter implements WaveformFilterInterface
{
    use TPriorityFilter;

    /** @var bool */
    private $downmix;
    /** @var int */
    private $priority;

    public function __construct(bool $downmix = false, int $priority = 0)
    {
        $this->downmix = $downmix;
        $this->priority = $priority;
    }

    public function getDownmix(): bool
    {
        return $this->downmix;
    }

    /**
     * @inheritDoc
     */
    public function apply(Waveform $waveform): array
    {
        if ($this->downmix) {
            foreach ($waveform->getAudio()->getStreams() as $stream) {
                if ($stream->isAudio()) {
                    return ['"aformat=channel_layouts=mono"'];
                }
            }
        }

        return [];
    }
}
