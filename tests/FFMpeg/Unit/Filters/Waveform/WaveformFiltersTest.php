<?php

namespace Tests\FFMpeg\Unit\Filters\Waveform;

use FFMpeg\Filters\Waveform\WaveformFilters;
use Tests\FFMpeg\Unit\TestCase;

class WaveformFiltersTest extends TestCase
{
    public function testResize()
    {
        $Waveform = $this->getWaveformMock();
        $filters = new WaveformFilters($Waveform);

        $Waveform->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Waveform\WaveformDownmixFilter'));

        $filters->setDownmix();
    }
}
