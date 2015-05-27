<?php

namespace FFMpeg\Tests\Filters\Video;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\Point;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Filters\Video\CropFilter;
use FFMpeg\Filters\Video\MergeFilter;
use FFMpeg\Tests\TestCase;

class MergeFilterTest extends TestCase
{

    public function testCommandParamsAreCorrectAndStreamIsUpdated()
    {
        $video = $this->getVideoMock();

        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $additionalFilePath = '/additional/file/path';
        $filter = new MergeFilter(array($additionalFilePath));
        $expected = array(
            '-i',
            $additionalFilePath,
            '-filter_complex',
            '[0:0] [0:1] [1:0] [1:1] concat=n=2:v=1:a=1 [v] [a]',
            '-map',
            '[v]',
            '-map',
            '[a]',
        );
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

}
