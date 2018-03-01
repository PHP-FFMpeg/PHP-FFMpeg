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

use FFMpeg\Media\Waveform;

class WaveformFilters
{
    private $waveform;

    public function __construct(Waveform $waveform)
    {
        $this->waveform = $waveform;
    }

    /**
     * Sets the downmix of the output waveform.
     *
     * If you want a simpler waveform, sets the downmix to TRUE.
     *
     * @return WaveformFilters
     */
    public function setDownmix()
    {
        $this->waveform->addFilter(new WaveformDownmixFilter());

        return $this;
    }
}
