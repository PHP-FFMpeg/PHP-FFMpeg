<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\AudioSampleFormatFilter;
use Tests\FFMpeg\Unit\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AudioSampleFormatFilterTest extends TestCase
{
    public function testGetFormat()
    {
        $filter = new AudioSampleFormatFilter('s16');
        static::assertSame('s16', $filter->getFormat());
    }

    public function testApply()
    {
        $audio = $this->getAudioMock();
        $format = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();

        $filter = new AudioSampleFormatFilter('s16');
        static::assertSame(['-sample_fmt', 's16'], $filter->apply($audio, $format));
    }
}
