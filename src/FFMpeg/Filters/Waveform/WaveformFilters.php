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
     * Fixes the display ratio of the output waveform.
     *
     * In case the sample ratio and display ratio are different, image may be
     * anamorphozed. This filter fixes this by specifying the output size.
     *
     * @return WaveformFilters
     */
    public function fixDisplayRatio()
    {
        $this->waveform->addFilter(new DisplayRatioFixerFilter());

        return $this;
    }
}
