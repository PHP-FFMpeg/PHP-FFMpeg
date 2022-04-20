<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use FFMpeg\Coordinate\AspectRatio;
use FFMpeg\Coordinate\Dimension;
use Tests\FFMpeg\Unit\TestCase;

class AspectRatioTest extends TestCase
{
    /**
     * @dataProvider provideDimensionsAndExpectedratio
     */
    public function testFromDimensions($width, $height, $strategy, $expected, $calculatedWidth, $calculatedHeight, $modulus = 2)
    {
        $ratio = AspectRatio::create(new Dimension($width, $height), $strategy);
        $this->assertEquals($expected, $ratio->getValue());

        $this->assertEquals($calculatedHeight, $ratio->calculateHeight(240, $modulus));
        $this->assertEquals($calculatedWidth, $ratio->calculateWidth(320, $modulus));
    }

    public function provideDimensionsAndExpectedratio()
    {
        return [
            //AR_5_4
            [720, 576, false, 5 / 4, 400, 192],
            [720, 577, false, 5 / 4, 400, 192],
            [720, 620, false, 720 / 620, 372, 206],
            [720, 576, true, 5 / 4, 400, 192],
            //AR_ROTATED_4_5
            [576, 720, false, 4 / 5, 256, 300],
            [576, 720, true, 4 / 5, 256, 300],
            //AR_4_3
            [320, 240, false, 4 / 3, 426, 180],
            [320, 240, true, 4 / 3, 426, 180],
            //AR_ROTATED_3_4
            [240, 320, false, 3 / 4, 240, 320],
            [240, 320, true, 3 / 4, 240, 320],
            //AR_16_9
            [1920, 1080, false, 16 / 9, 568, 136],
            [1920, 1080, true, 16 / 9, 568, 136],
            [1280, 720, false, 16 / 9, 568, 136],
            [1280, 720, true, 16 / 9, 568, 136],
            [3840, 2160, false, 16 / 9, 568, 136],
            [3840, 2160, true, 16 / 9, 568, 136],
            // modulus 4
            [1920, 1080, false, 16 / 9, 568, 136, 4],
            [1920, 1080, true, 16 / 9, 568, 136, 4],
            [1280, 720, false, 16 / 9, 568, 136, 4],
            [1280, 720, true, 16 / 9, 568, 136, 4],
            [3840, 2160, false, 16 / 9, 568, 136, 4],
            [3840, 2160, true, 16 / 9, 568, 136, 4],
            // modulus 16
            [1920, 1080, false, 16 / 9, 576, 128, 16],
            [1920, 1080, true, 16 / 9, 576, 128, 16],
            [1280, 720, false, 16 / 9, 576, 128, 16],
            [1280, 720, true, 16 / 9, 576, 128, 16],
            [3840, 2160, false, 16 / 9, 576, 128, 16],
            [3840, 2160, true, 16 / 9, 576, 128, 16],
            //AR_ROTATED_9_16
            [1080, 1920, false, 9 / 16, 180, 426],
            [1080, 1920, true, 9 / 16, 180, 426],
            [720, 1280, false, 9 / 16, 180, 426],
            [720, 1280, true, 9 / 16, 180, 426],
            [2160, 3840, false, 9 / 16, 180, 426],
            [2160, 3840, true, 9 / 16, 180, 426],
            //AR_3_2
            [360, 240, false, 3 / 2, 480, 160],
            [360, 240, true, 3 / 2, 480, 160],
            //AR_ROTATED_2_3
            [240, 360, false, 2 / 3, 214, 360],
            [240, 360, true, 2 / 3, 214, 360],
            //AR_5_3
            //AR_ROTATED_3_5
            //AR_1_1
            //AR_1_DOT_85_1
            //AR_ROTATED_1_DOT_85
            //AR_2_DOT_39_1
            //AR_ROTATED_2_DOT_39
        ];
    }
}
