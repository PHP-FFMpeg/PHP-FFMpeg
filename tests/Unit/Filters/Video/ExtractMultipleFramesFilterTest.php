<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\ExtractMultipleFramesFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

class ExtractMultipleFramesFilterTest extends TestCase {
    /**
     * @dataProvider provideFrameRates
     */
    public function testApply($frameRate, $frameFileType, $modulus, $expected) {
        $video = $this->getVideoMock();
        $pathfile = '/path/to/file' . mt_rand();

        $format = $this->getMockBuilder(\FFMpeg\Format\VideoInterface::class)->getMock();
        $format->expects($this->any())
            ->method('getModulus')
            ->will($this->returnValue($modulus));

        $filter = new ExtractMultipleFramesFilter($frameRate, $frameFileType);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function provideFrameRates() {
        return [
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, 'jpg', 2, ['-vf', 'fps=1/1']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, 'jpg', 2, ['-vf', 'fps=1/2']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, 'jpg', 2, ['-vf', 'fps=1/5']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, 'jpg', 2, ['-vf', 'fps=1/10']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, 'jpg', 2, ['-vf', 'fps=1/30']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, 'jpg', 2, ['-vf', 'fps=1/60']],

            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, 'jpeg', 2, ['-vf', 'fps=1/1']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, 'jpeg', 2, ['-vf', 'fps=1/2']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, 'jpeg', 2, ['-vf', 'fps=1/5']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, 'jpeg', 2, ['-vf', 'fps=1/10']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, 'jpeg', 2, ['-vf', 'fps=1/30']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, 'jpeg', 2, ['-vf', 'fps=1/60']],

            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_SEC, 'png', 2, ['-vf', 'fps=1/1']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, 'png', 2, ['-vf', 'fps=1/2']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_5SEC, 'png', 2, ['-vf', 'fps=1/5']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, 'png', 2, ['-vf', 'fps=1/10']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_30SEC, 'png', 2, ['-vf', 'fps=1/30']],
            [ExtractMultipleFramesFilter::FRAMERATE_EVERY_60SEC, 'png', 2, ['-vf', 'fps=1/60']],
        ];
    }
}
