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
    /**
     * @var Waveform
     */
    private $waveform;

    public function __construct(Waveform $waveform)
    {
        $this->waveform = $waveform;
    }

    /**
     * Sets the downmix of the output waveform.
     *
     * @return WaveformFilters
     */
    public function setDownmix() : WaveformFilters
    {
        $this->waveform->addFilter(new WaveformDownmixFilter());

        return $this;
    }
}
