<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\AdvancedMedia\TestSrcFilter;
use FFMpeg\Filters\AdvancedMedia\XStackFilter;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Video\X264;

class AdvancedMediaTest extends FunctionalTestCase
{
    /**
     * Path prefix to avoid conflicts with another tests.
     */
    const OUTPUT_PATH_PREFIX = 'output/advanced_media_';

    public function testRunWithoutComplexFilterTestExtractAudio()
    {
        $ffmpeg = $this->getFFMpeg();
        $inputs = array(realpath(__DIR__ . '/../files/Test.ogv'));
        $format = new Mp3();
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'extracted_with_map.mp3';

        // You can run it without -filter_complex, just using -map.
        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia
            ->map(array('0:a'), $format, $output)
            ->save();

        $this->assertFileExists($output);
        $this->assertEquals('MP2/3 (MPEG audio layer 2/3)',
            $ffmpeg->open($output)->getFormat()->get('format_long_name'));
        unlink($output);
    }

    public function testAudio()
    {
        $ffmpeg = $this->getFFMpeg();
        $inputs = array(realpath(__DIR__ . '/../files/Audio.mp3'));
        $format = new Mp3();
        $format->setAudioKiloBitrate(30);
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'audio_test.mp3';

        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia
            ->map(array('0:a'), $format, $output)
            ->save();

        $this->assertFileExists($output);
        $this->assertEquals('MP2/3 (MPEG audio layer 2/3)',
            $ffmpeg->open($output)->getFormat()->get('format_long_name'));
        unlink($output);
    }

    public function testMultipleInputs()
    {
        $ffmpeg = $this->getFFMpeg();
        $inputs = array(
            realpath(__DIR__ . '/../files/portrait.MOV'),
            realpath(__DIR__ . '/../files/portrait.MOV')
        );
        $format = new X264('aac', 'libx264');
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'multiple_inputs_test.mp4';

        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia->filters()
            ->custom('[0:v][1:v]', 'hstack', '[v]');
        $advancedMedia
            ->map(array('0:a', '[v]'), $format, $output)
            ->save();

        $this->assertFileExists($output);
        $this->assertEquals('QuickTime / MOV',
            $ffmpeg->open($output)->getFormat()->get('format_long_name'));
        unlink($output);
    }

    /**
     * @covers \FFMpeg\Media\AdvancedMedia::map
     */
    public function testMultipleOutputsTestAbsenceOfInputs()
    {
        $ffmpeg = $this->getFFMpeg();
        // in this test we use only computed inputs
        // and can ignore -i part of the command, pass empty inputs array.
        $inputs = array();
        $formatX264 = new X264('aac', 'libx264');
        $formatMp3 = new Mp3();

        $outputMp3 = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'test_multiple_outputs.mp3';
        $outputVideo1 = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'test_multiple_outputs_v1.mp4';
        $outputVideo2 = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'test_multiple_outputs_v2.mp4';

        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia->filters()
            ->sine('[a]', 5)
            ->testSrc('[v1]', TestSrcFilter::TESTSRC, '160x120', 5)
            ->testSrc('[v2]', TestSrcFilter::TESTSRC, '160x120', 5)
            ->custom('[v1]', 'negate', '[v1negate]')
            ->custom('[v2]', 'edgedetect', '[v2edgedetect]');
        $advancedMedia
            ->map(array('[a]'), $formatMp3, $outputMp3)
            ->map(array('[v1negate]'), $formatX264, $outputVideo1)
            ->map(array('[v2edgedetect]'), $formatX264, $outputVideo2)
            ->save();


        $this->assertFileExists($outputMp3);
        $this->assertEquals('MP2/3 (MPEG audio layer 2/3)',
            $ffmpeg->open($outputMp3)->getFormat()->get('format_long_name'));
        unlink($outputMp3);

        $this->assertFileExists($outputVideo1);
        $this->assertEquals('QuickTime / MOV',
            $ffmpeg->open($outputVideo1)->getFormat()->get('format_long_name'));
        unlink($outputVideo1);

        $this->assertFileExists($outputVideo2);
        $this->assertEquals('QuickTime / MOV',
            $ffmpeg->open($outputVideo2)->getFormat()->get('format_long_name'));
        unlink($outputVideo2);
    }

    /**
     * @covers \FFMpeg\Filters\AdvancedMedia\TestSrcFilter
     * @covers \FFMpeg\Filters\AdvancedMedia\SineFilter
     */
    public function testTestSrcFilterTestSineFilter()
    {
        $ffmpeg = $this->getFFMpeg();
        $inputs = array(realpath(__DIR__ . '/../files/Test.ogv'));
        $format = new X264('aac', 'libx264');
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'testsrc.mp4';

        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia->filters()
            ->sine('[a]', 10)
            ->testSrc('[v]', TestSrcFilter::TESTSRC, '160x120', 10);
        $advancedMedia
            ->map(array('[a]', '[v]'), $format, $output)
            ->save();

        $this->assertFileExists($output);
        $this->assertEquals('QuickTime / MOV',
            $ffmpeg->open($output)->getFormat()->get('format_long_name'));
        unlink($output);
    }

    /**
     * XStack filter is supported starting from 4.1 ffmpeg version.
     *
     * @covers \FFMpeg\Filters\AdvancedMedia\XStackFilter
     * @covers \FFMpeg\Filters\AdvancedMedia\SineFilter
     */
    public function testXStackFilter()
    {
        $xStack = new XStackFilter('', 0);
        $ffmpeg = $this->getFFMpeg();
        $ffmpegVersion = $ffmpeg->getFFMpegDriver()->getVersion();
        if (version_compare($ffmpegVersion, $xStack->getMinimalFFMpegVersion(), '<')) {
            $this->markTestSkipped('XStack filter is supported starting from ffmpeg version '
                . $xStack->getMinimalFFMpegVersion() . ', your version is '
                . $ffmpegVersion);
            return;
        }

        $inputs = array(realpath(__DIR__ . '/../files/Test.ogv'));
        $format = new X264('aac', 'libx264');
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'xstack_test.mp4';

        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia->filters()
            ->sine('[a]', 5)
            ->testSrc('[v1]', TestSrcFilter::TESTSRC, '160x120', 5)
            ->testSrc('[v2]', TestSrcFilter::TESTSRC, '160x120', 5)
            ->testSrc('[v3]', TestSrcFilter::TESTSRC, '160x120', 5)
            ->testSrc('[v4]', TestSrcFilter::TESTSRC, '160x120', 5)
            ->xStack('[v1][v2][v3][v4]',
                XStackFilter::LAYOUT_2X2, 4, '[v]');
        $advancedMedia
            ->map(array('[a]', '[v]'), $format, $output)
            ->save();

        $this->assertFileExists($output);
        $this->assertEquals('QuickTime / MOV',
            $ffmpeg->open($output)->getFormat()->get('format_long_name'));
        unlink($output);
    }

    public function testOfCompatibilityWithExistedFilters()
    {
        $ffmpeg = $this->getFFMpeg();
        $inputs = array(realpath(__DIR__ . '/../files/Test.ogv'));
        $watermark = realpath(__DIR__ . '/../files/watermark.png');
        $format = new X264('aac', 'libx264');
        $output = __DIR__ . '/' . self::OUTPUT_PATH_PREFIX . 'test_of_compatibility_with_existed_filters.mp4';

        $advancedMedia = $ffmpeg->openAdvanced($inputs);
        $advancedMedia->filters()
            // For unknown reasons WatermarkFilter produce an error on Windows,
            // because the path to the watermark becomes corrupted.
            // This behaviour related with Alchemy\BinaryDriver\AbstractBinary::command().
            // The path inside filter becomes like
            // "D:ServerswwwPHP-FFMpegtestsfileswatermark.png" (without slashes).
            // But on Linux systems filter works as expected.
            //->watermark('[0:v]', $watermark, '[v]')
            ->pad('[0:v]', new Dimension(300, 100), '[v]');
        $advancedMedia
            ->map(array('0:a', '[v]'), $format, $output)
            ->save();

        $this->assertFileExists($output);
        $this->assertEquals('QuickTime / MOV',
            $ffmpeg->open($output)->getFormat()->get('format_long_name'));
        unlink($output);
    }

    public function testForceDisableAudio()
    {
        $ffmpeg = $this->getFFMpeg();
        $format = new X264();

        $advancedMedia1 = $ffmpeg->openAdvanced(array(__FILE__));
        $advancedMedia1
            ->map(array('test'), $format, 'outputFile.mp4', false);
        $this->assertStringContainsString('acodec', $advancedMedia1->getFinalCommand());

        $advancedMedia2 = $ffmpeg->openAdvanced(array(__FILE__));
        $advancedMedia2
            ->map(array('test'), $format, 'outputFile.mp4', true);
        $this->assertStringNotContainsString('acodec', $advancedMedia2->getFinalCommand());
    }

    public function testForceDisableVideo()
    {
        $ffmpeg = $this->getFFMpeg();
        $format = new X264();

        $advancedMedia1 = $ffmpeg->openAdvanced(array(__FILE__));
        $advancedMedia1->map(array('test'), $format,
            'outputFile.mp4', false, false);
        $this->assertStringContainsString('vcodec', $advancedMedia1->getFinalCommand());

        $advancedMedia2 = $ffmpeg->openAdvanced(array(__FILE__));
        $advancedMedia2->map(array('test'), $format,
            'outputFile.mp4', false, true);
        $this->assertStringNotContainsString('vcodec', $advancedMedia2->getFinalCommand());
    }

    public function testGlobalOptions()
    {
        $configuration = array(
            'ffmpeg.threads' => 3,
            'ffmpeg.filter_threads' => 13,
            'ffmpeg.filter_complex_threads' => 24,
        );

        $ffmpeg = $this->getFFMpeg($configuration);
        $advancedMedia = $ffmpeg->openAdvanced(array(__FILE__));
        $command = $advancedMedia->getFinalCommand();

        foreach ($configuration as $optionName => $optionValue) {
            $optionName = str_replace('ffmpeg.', '', $optionName);
            $this->assertStringContainsString('-' . $optionName . ' ' . $optionValue, $command);
        }
    }
}
