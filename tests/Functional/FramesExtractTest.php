<?php
/*
 * This file is part of PHP-FFmpeg.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\FFMpeg\Functional;


use FFMpeg\Media\Frames;

class FramesExtractTest extends FunctionalTestCase
{
    /**
     * @param int $framesPerSecond
     * @param int $expectedImages
     *
     * @dataProvider framesProvider
     */
    public function testFramesCount($framesPerSecond, $expectedImages)
    {
        $saveFolder = __DIR__ . '/output/';
        $filenameTemplate = $saveFolder . 'frame-%02d.jpg';
        $files = array();
        for ($i=1; $i < $expectedImages + 1; $i++) {
            $filename = sprintf($filenameTemplate, $i);
            if (is_file($filename)) {
                unlink($filename);
            }
            $files[] = $filename;
        }

        $ffmpeg = $this->getFFMpeg();
        $video = $ffmpeg->open(__DIR__ . '/../files/Test.ogv');

        $this->assertInstanceOf('FFMpeg\Media\Video', $video);

        $frames = $video->frames($framesPerSecond);

        $frames->save($saveFolder);
        for ($i=1; $i < $expectedImages + 1; $i++) {
            $filename = sprintf($filenameTemplate, $i);
            $this->assertFileExists($filename);
            unlink($filename);
        }

        // next frame was not fetched
        $this->assertFileNotExists(sprintf($filenameTemplate, $i));
    }

    public function framesProvider()
    {
        $calls = array();

        // video length is 00:00:29.53 and ffmpeg catches frame on second 0
        $calls[] = array(Frames::FRAMERATE_EVERY_2SEC, 16);
        $calls[] = array(Frames::FRAMERATE_EVERY_SEC, 30);

        return $calls;
    }
}
