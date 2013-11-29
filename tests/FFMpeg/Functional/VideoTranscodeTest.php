<?php

namespace FFMpeg\Functional;

use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Video;

class VideoTranscodeTest extends FunctionalTestCase
{
    public function testSimpleTranscodeX264()
    {
        $filename = __DIR__ . '/output/output-x264.mp4';
        if (is_file($filename)) {
            unlink(__DIR__ . '/output/output-x264.mp4');
        }

        $ffmpeg = $this->getFFMpeg();
        $video = $ffmpeg->open(__DIR__ . '/../../files/Test.ogv');

        $this->assertInstanceOf('FFMpeg\Media\Video', $video);

        $lastPercentage = null;
        $phpunit = $this;

        $codec = new X264('libvo_aacenc');
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

    /**
     * @expectedException \FFMpeg\Exception\RuntimeException
     */
    public function testTranscodeInvalidFile()
    {
        $ffmpeg = $this->getFFMpeg();
        $ffmpeg->open(__DIR__ . '/../../files/UnknownFileTest.ogv');
    }

    public function testSaveInvalidForgedVideo()
    {
        $ffmpeg = $this->getFFMpeg();
        $video = new Video(__DIR__ . '/../../files/UnknownFileTest.ogv', $ffmpeg->getFFMpegDriver(), $ffmpeg->getFFProbe());

        $this->setExpectedException('FFMpeg\Exception\RuntimeException');
        $video->save(new X264('libvo_aacenc'), __DIR__ . '/output/output-x264.mp4');
    }
}
