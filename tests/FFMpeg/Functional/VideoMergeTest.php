<?php

namespace FFMpeg\Functional;


use FFMpeg\Format\Video\Ogg;

class VideoMergeTest extends FunctionalTestCase
{
    public function testSimpleMerge()
    {
        $filename = __DIR__ . '/output/output-merged.ogv';
        if (is_file($filename)) {
            unlink(__DIR__ . '/output/output-merged.ogv');
        }

        $ffmpeg = $this->getFFMpeg();

        $inputFile = __DIR__ . '/../../files/Test.ogv';
        $video = $ffmpeg->open($inputFile);

        $this->assertInstanceOf('FFMpeg\Media\Video', $video);

        $video->filters()
            ->merge(array($inputFile));

        $lastPercentage = null;
        $phpunit = $this;

        $codec = new Ogg();
        $codec->on('progress', function ($video, $codec, $percentage) use ($phpunit, &$lastPercentage) {
            if (null !== $lastPercentage) {
                $phpunit->assertGreaterThanOrEqual($lastPercentage, $percentage);
            }
            $lastPercentage = $percentage;
            $phpunit->assertGreaterThanOrEqual(0, $percentage);
            $phpunit->assertLessThanOrEqual(100, $percentage);
        });

        $video->save($codec, $filename);
        $this->assertFileExists($filename);
        unlink($filename);
    }
}
