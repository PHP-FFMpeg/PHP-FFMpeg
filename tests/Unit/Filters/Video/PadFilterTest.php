<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\PadFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\Dimension;

class PadFilterTest extends TestCase
{
    /**
     * @dataProvider provideDimensions
     */
    public function testApply(Dimension $dimension, $expected)
    {
        $video = $this->getVideoMock();

        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();

        $filter = new PadFilter($dimension);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function provideDimensions()
    {
        return array(
            array(new Dimension(1000, 800), array('-vf', 'scale=iw*min(1000/iw\,800/ih):ih*min(1000/iw\,800/ih),pad=1000:800:(1000-iw)/2:(800-ih)/2')),
            array(new Dimension(300, 600), array('-vf', 'scale=iw*min(300/iw\,600/ih):ih*min(300/iw\,600/ih),pad=300:600:(300-iw)/2:(600-ih)/2')),
            array(new Dimension(100, 900), array('-vf', 'scale=iw*min(100/iw\,900/ih):ih*min(100/iw\,900/ih),pad=100:900:(100-iw)/2:(900-ih)/2')),
            array(new Dimension(1200, 200), array('-vf', 'scale=iw*min(1200/iw\,200/ih):ih*min(1200/iw\,200/ih),pad=1200:200:(1200-iw)/2:(200-ih)/2')),
        );
    }
}
