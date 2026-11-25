<?php
namespace Tests\Functional;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Aac;
use FFMpeg\Format\Video\X264;
use PHPUnit\Framework\TestCase;

class ForceFormatIntegrationTest extends TestCase
{
    private string $fixturesDir;
    private string $outputDir;

    protected function setUp(): void
    {

        $this->fixturesDir = __DIR__ . '/../../files';

        $this->outputDir = __DIR__ . '/../../Functional/output';
        if (! file_exists($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        foreach (glob($this->outputDir . '/*') as $file) {
            unlink($file);
        }
        if (is_dir($this->outputDir)) {
            rmdir($this->outputDir);
        }
    }

    /**
     * Helper method: Check if a given encoder is available in the current FFmpeg build.
     */
    private function isEncoderAvailable(string $encoder): bool
    {
        exec('/usr/bin/ffmpeg -encoders', $output);
        $outputString = implode("\n", $output);
        return strpos($outputString, $encoder) !== false;
    }

    /**
     * Test that for audio, with forced format enabled, even if the output filename has a mismatched extension (.jpg),
     * the produced container is MP4.
     */
    public function testAudioForcedFormatWithMismatchedExtension()
    {
        if (! $this->isEncoderAvailable('libfdk_aac')) {
            $this->markTestSkipped('libfdk_aac encoder is not available in this FFmpeg build.');
        }

        $ffmpeg      = FFMpeg::create();
        $audioSource = $this->fixturesDir . '/Audio.mp3';
        $audio       = $ffmpeg->open($audioSource);

        $format = new Aac();
        $format->setForceFormat(true);

        // Even though the file is named with a .jpg extension, forced formatting should override it.
        $outputFile = $this->outputDir . '/output_audio_forced_mismatched.jpg';
        $audio->save($format, $outputFile);

        // Use FFProbe to inspect the output container.
        $ffprobe    = FFProbe::create();
        $formatInfo = $ffprobe->format($outputFile);
        $container  = $formatInfo->get('format_name');

        // For Aac with forced format enabled, we expect the container to include "mp4" (producing an M4A file).
        $this->assertStringContainsString(
            'mp4',
            $container,
            "Forced format should yield an MP4 container even if the extension is .jpg."
        );
    }

    /**
     * Test that for audio, with forced format enabled and no extension on the output filename,
     * the produced file still uses the MP4 container.
     */
    public function testAudioForcedFormatWithNoExtension()
    {
        if (! $this->isEncoderAvailable('libfdk_aac')) {
            $this->markTestSkipped('libfdk_aac encoder is not available in this FFmpeg build.');
        }

        $ffmpeg      = FFMpeg::create();
        $audioSource = $this->fixturesDir . '/Audio.mp3';
        $audio       = $ffmpeg->open($audioSource);

        $format = new Aac();
        $format->setForceFormat(true);

        $outputFile = $this->outputDir . '/output_audio_forced_noext';
        $audio->save($format, $outputFile);

        $ffprobe    = FFProbe::create();
        $formatInfo = $ffprobe->format($outputFile);
        $container  = $formatInfo->get('format_name');

        $this->assertStringContainsString(
            'mp4',
            $container,
            "Forced format should yield an MP4 container even when no extension is provided."
        );
    }

    /**
     * Test that when forced format is disabled for audio,
     * the output container is determined by the file extension.
     * Here, we use ".aac" (a valid audio extension) so that FFmpeg uses the extension.
     */
    public function testAudioWithoutForcedFormatUsesExtension()
    {
        if (! $this->isEncoderAvailable('libfdk_aac')) {
            $this->markTestSkipped('libfdk_aac encoder is not available in this FFmpeg build.');
        }

        $ffmpeg      = FFMpeg::create();
        $audioSource = $this->fixturesDir . '/Audio.mp3';
        $audio       = $ffmpeg->open($audioSource);

        $format = new Aac();
        $format->setForceFormat(false);

        $outputFile = $this->outputDir . '/output_audio_no_force.aac';
        $audio->save($format, $outputFile);

        $ffprobe    = FFProbe::create();
        $formatInfo = $ffprobe->format($outputFile);
        $container  = $formatInfo->get('format_name');

        // Without forced format, the container should follow the extension (.aac) and not include "mp4".
        $this->assertStringNotContainsString(
            'mp4',
            $container,
            "Without forced format, the container should be determined by the .aac extension."
        );
    }

    /**
     * Test that for video, with forced format enabled, even if the output file extension does not match (e.g., .avi),
     * the output container is MP4.
     */
    public function testVideoForcedFormatWithMismatchedExtension()
    {
        $videoSource = $this->fixturesDir . '/Test.ogv';
        if (! file_exists($videoSource)) {
            $this->markTestSkipped("Video fixture not found at {$videoSource}");
        }

        $ffmpeg = FFMpeg::create();
        $video  = $ffmpeg->open($videoSource);

        $format = new X264();
        $format->setForceFormat(true);

        $outputFile = $this->outputDir . '/output_video_forced_mismatched.avi';
        $video->save($format, $outputFile);

        $ffprobe    = FFProbe::create();
        $formatInfo = $ffprobe->format($outputFile);
        $container  = $formatInfo->get('format_name');

        // With forced format enabled, we expect the container to be MP4.
        $this->assertStringContainsString(
            'mp4',
            $container,
            "Forced format should yield an MP4 container for video even if the extension is .avi."
        );
    }

    /**
     * Test that for video, with forced format enabled and no extension provided,
     * the produced file still uses the MP4 container.
     */
    public function testVideoForcedFormatWithNoExtension()
    {
        $videoSource = $this->fixturesDir . '/Test.ogv';
        if (! file_exists($videoSource)) {
            $this->markTestSkipped("Video fixture not found at {$videoSource}");
        }

        $ffmpeg = FFMpeg::create();
        $video  = $ffmpeg->open($videoSource);

        $format = new X264();
        $format->setForceFormat(true);

        $outputFile = $this->outputDir . '/output_video_forced_noext';
        $video->save($format, $outputFile);

        $ffprobe    = FFProbe::create();
        $formatInfo = $ffprobe->format($outputFile);
        $container  = $formatInfo->get('format_name');

        $this->assertStringContainsString(
            'mp4',
            $container,
            "Forced format should yield an MP4 container for video even with no extension."
        );
    }
}
