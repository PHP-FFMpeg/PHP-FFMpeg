<?php

namespace Tests\FFMpeg\Unit\Filters\Waveform;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Filters\Waveform\WaveformDownmixFilter;
use FFMpeg\Media\Waveform;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\FFProbe\DataMapping\Stream;

class WaveformDownmixFilterTest extends TestCase
{
    public function testApply()
    {
        $stream = new Stream(array('codec_type' => 'audio', 'width' => 960, 'height' => 720));
        $streams = new StreamCollection(array($stream));

        $audio = $this->getAudioMock(__FILE__);
        $audio->expects($this->once())
                ->method('getStreams')
                ->will($this->returnValue($streams));

        $waveform = new Waveform($audio, $this->getFFMpegDriverMock(), $this->getFFProbeMock(), 640, 120);
        $filter = new WaveformDownmixFilter(TRUE);
        $this->assertEquals(array('"aformat=channel_layouts=mono"'), $filter->apply($waveform));
    }
}
