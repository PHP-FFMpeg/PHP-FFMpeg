<?php

namespace FFMpeg\Tests\Coordinate;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Tests\TestCase;
use FFMpeg\Coordinate\AspectRatio;

class AspectRatioTest extends TestCase
{
    /**
     * @dataProvider provideDimensionsAndExpectedratio
     */
    public function testFromDimensions($width, $height, $strategy, $expected)
    {
        $ratio = AspectRatio::fromDimensions(new Dimension($width, $height), $strategy);
        $this->assertEquals($expected, $ratio->getValue());
    }

    public function provideDimensionsAndExpectedratio()
    {
        return array(
            //AR_5_4
            array(720, 576, AspectRatio::STRATEGY_CUSTOM, 5/4),
            array(720, 577, AspectRatio::STRATEGY_CUSTOM, 5/4),
            array(720, 620, AspectRatio::STRATEGY_CUSTOM, 720/620),
            array(720, 576, AspectRatio::STRATEGY_NEAREST, 5/4),
            //AR_ROTATED_4_5
            array(576, 720, AspectRatio::STRATEGY_CUSTOM, 4/5),
            array(576, 720, AspectRatio::STRATEGY_NEAREST, 4/5),
            //AR_4_3
            array(320, 240, AspectRatio::STRATEGY_CUSTOM, 4/3),
            array(320, 240, AspectRatio::STRATEGY_NEAREST, 4/3),
            //AR_ROTATED_3_4
            array(240, 320, AspectRatio::STRATEGY_CUSTOM, 3/4),
            array(240, 320, AspectRatio::STRATEGY_NEAREST, 3/4),
            //AR_16_9
            array(1920, 1080, AspectRatio::STRATEGY_CUSTOM, 16/9),
            array(1920, 1080, AspectRatio::STRATEGY_NEAREST, 16/9),
            array(1280, 720, AspectRatio::STRATEGY_CUSTOM, 16/9),
            array(1280, 720, AspectRatio::STRATEGY_NEAREST, 16/9),
            array(3840, 2160, AspectRatio::STRATEGY_CUSTOM, 16/9),
            array(3840, 2160, AspectRatio::STRATEGY_NEAREST, 16/9),
            //AR_ROTATED_9_16
            array(1080, 1920, AspectRatio::STRATEGY_CUSTOM, 9/16),
            array(1080, 1920, AspectRatio::STRATEGY_NEAREST, 9/16),
            array(720, 1280, AspectRatio::STRATEGY_CUSTOM, 9/16),
            array(720, 1280, AspectRatio::STRATEGY_NEAREST, 9/16),
            array(2160, 3840, AspectRatio::STRATEGY_CUSTOM, 9/16),
            array(2160, 3840, AspectRatio::STRATEGY_NEAREST, 9/16),
            //AR_3_2
            array(360, 240, AspectRatio::STRATEGY_CUSTOM, 3/2),
            array(360, 240, AspectRatio::STRATEGY_NEAREST, 3/2),
            //AR_ROTATED_2_3
            array(240, 360, AspectRatio::STRATEGY_CUSTOM, 2/3),
            array(240, 360, AspectRatio::STRATEGY_NEAREST, 2/3),
            //AR_5_3
            //AR_ROTATED_3_5
            //AR_1_1
            //AR_1_DOT_85_1
            //AR_ROTATED_1_DOT_85
            //AR_2_DOT_39_1
            //AR_ROTATED_2_DOT_39
        );
    }
}
