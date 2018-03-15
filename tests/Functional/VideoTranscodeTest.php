<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Filters\Video\RotateFilter;
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
        $video = $ffmpeg->open(__DIR__ . '/../files/Test.ogv');

        $this->assertInstanceOf('FFMpeg\Media\Video', $video);

        $lastPercentage = null;
        $phpunit = $this;

        $codec = new X264('aac');
        $codec->on('progress', function ($video, $codec, $percentage) use ($phpunit, &$lastPercentage) {
            if (null !== $lastPercentage) {
                // allow catching up on smaller systems
                $phpunit->assertGreaterThanOrEqual($lastPercentage - 10, $percentage);
            }
            $lastPercentage = $percentage;
            $phpunit->assertGreaterThanOrEqual(0, $percentage);
            $phpunit->assertLessThanOrEqual(100, $percentage);
        });

        $video->save($codec, $filename);
        $this->assertFileExists($filename);
        unlink($filename);
    }

    public function testAacTranscodeX264()
    {
        $filename = __DIR__ . '/output/output-x264_2.mp4';
        if (is_file($filename)) {
            unlink(__DIR__ . '/output/output-x264_2.mp4');
        }

        $ffmpeg = $this->getFFMpeg();
        $video = $ffmpeg->open(__DIR__ . '/../files/sample.3gp');

        $this->assertInstanceOf(FFMpeg\Media\Video::class, $video);

        $lastPercentage = null;
        $phpunit = $this;

        $codec = new X264('aac');
        $codec->on('progress', function ($video, $codec, $percentage) use ($phpunit, &$lastPercentage) {
            if (null !== $lastPercentage) {
                // allow catching up on smaller systems
                $phpunit->assertGreaterThanOrEqual($lastPercentage - 10, $percentage);
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
        $ffmpeg->open(__DIR__ . '/../files/UnknownFileTest.ogv');
    }

    /**
     * @expectedException \FFMpeg\Exception\RuntimeException
     */
    public function testSaveInvalidForgedVideo()
    {
        $ffmpeg = $this->getFFMpeg();
        $video = new Video(__DIR__ . '/../files/UnknownFileTest.ogv', $ffmpeg->getFFMpegDriver(), $ffmpeg->getFFProbe());

        $video->save(new X264('aac'), __DIR__ . '/output/output-x264.mp4');
    }

    public function testTranscodePortraitVideo()
    {
        $info = $this->getNameAndVersion();

        if ($info['name'] === 'avconv' && version_compare($info['version'], '0.9', '<')) {
            $this->markTestSkipped('This version of avconv is buggy and does not support this test.');
        }

        $filename = __DIR__ . '/output/output-x264.mp4';
        if (is_file($filename)) {
            unlink(__DIR__ . '/output/output-x264.mp4');
        }

        $ffmpeg = $this->getFFMpeg();
        $video = $ffmpeg->open(__DIR__ . '/../files/portrait.MOV');

        $video->filters()
            ->resize(new Dimension(320, 240), ResizeFilter::RESIZEMODE_INSET)
            ->rotate(RotateFilter::ROTATE_90);
        $video->save(new X264('aac'), $filename);

        $dimension = $ffmpeg->getFFProbe()
            ->streams($filename)
            ->videos()
            ->first()
            ->getDimensions();

        $this->assertLessThan(1, $dimension->getRatio(false)->getValue());
        $this->assertEquals(240, $dimension->getHeight());

        $this->assertFileExists($filename);
        unlink($filename);
    }

    private function getNameAndVersion()
    {
        $binary = $this
            ->getFFMpeg()
            ->getFFMpegDriver()
            ->getProcessBuilderFactory()
            ->getBinary();

        $output = $matches = $name = $version = null;
        exec($binary . ' -version 2>&1', $output);

        preg_match('/^([a-z]+)\s+version\s+([0-9\.]+)/i', $output[0], $matches);

        if (count($matches)) {
            $name = $matches[1];
            $version = $matches[2];
        }

        return ['name' => $name, 'version' => $version];
    }
}
