<?php

namespace Tests\FFMpeg\Unit\Filters\Waveform;

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Filters\Waveform\WaveformDownmixFilter;
use FFMpeg\Media\Waveform;
use Tests\FFMpeg\Unit\TestCase;

class WaveformDownmixFilterTest extends TestCase
{
    public function testApply()
    {
        $stream = new Stream(['codec_type' => 'audio', 'width' => 960, 'height' => 720]);
        $streams = new StreamCollection([$stream]);

        $audio = $this->getAudioMock(__FILE__);
        $audio->expects($this->once())
                ->method('getStreams')
                ->will($this->returnValue($streams));

        $waveform = new Waveform($audio, $this->getFFMpegDriverMock(), $this->getFFProbeMock(), 640, 120);
        $filter = new WaveformDownmixFilter(true);
        $this->assertEquals(['"aformat=channel_layouts=mono"'], $filter->apply($waveform));
    }
}
