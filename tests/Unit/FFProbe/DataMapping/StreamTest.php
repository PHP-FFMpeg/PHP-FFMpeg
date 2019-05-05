<?php
declare(strict_types=1);
namespace Tests\FFMpeg\Unit\FFProbe\DataMapping;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFProbe\DataMapping\Stream;
use Tests\FFMpeg\Unit\TestCase;

class StreamTest extends TestCase
{
    /**
     * @dataProvider provideAudioCases
     */
    public function testIsAudio($isAudio, $properties): void
    {
        $stream = new Stream($properties);
        $this->assertSame($isAudio, $stream->isAudio());
    }

    public function provideAudioCases(): array
    {
        return [
            [true, ['codec_type' => 'audio']],
            [false, ['codec_type' => 'video']],
        ];
    }

    /**
     * @dataProvider provideVideoCases
     */
    public function testIsVideo($isVideo, $properties): void
    {
        $stream = new Stream($properties);
        $this->assertSame($isVideo, $stream->isVideo());
    }

    public function provideVideoCases()
    {
        return [
            [true, ['codec_type' => 'video']],
            [false, ['codec_type' => 'audio']],
        ];
    }

    /**
     * @expectedException FFMpeg\Exception\LogicException
     * @expectedExceptionMessage Dimensions can only be retrieved from video streams.
     */
    public function testGetDimensionsFromAudio()
    {
        $stream = new Stream(['codec_type' => 'audio']);
        $stream->getDimensions();
    }

    public function testGetDimensionsFromVideo()
    {
        $stream = new Stream(['codec_type' => 'video', 'width' => 960, 'height' => 720]);
        $this->assertEquals(new Dimension(960, 720), $stream->getDimensions());
    }

    /**
     * @dataProvider provideInvalidPropertiesForDimensionsExtraction
     * @expectedException FFMpeg\Exception\RuntimeException
     * @expectedExceptionMessage Unable to extract dimensions.
     */
    public function testUnableToGetDimensionsFromVideo($properties): void
    {
        $stream = new Stream(['codec_type' => 'video', 'width' => 960]);
        $stream->getDimensions();
    }

    public function provideInvalidPropertiesForDimensionsExtraction(): array
    {
        return [
            ['codec_type' => 'video', 'width' => 960],
            ['codec_type' => 'video', 'height' => 960],
        ];
    }

    /**
     * @dataProvider providePropertiesForDimensionsExtraction
     */
    public function testGetDimensionsFromVideoWithDisplayRatio($data): void
    {
        $stream = new Stream([
            'codec_type' => 'video',
            'width' => $data['width'],
            'height' => $data['height'],
            'sample_aspect_ratio' => $data['sar'],
            'display_aspect_ratio' => $data['dar'],
        ]);
        $this->assertEquals(new Dimension($data['result_width'], $data['result_height']), $stream->getDimensions());
    }

    /**
     * @dataProvider provideInvalidRatios
     */
    public function testGetDimensionsFromVideoWithInvalidDisplayRatio($invalidRatio): void
    {
        $stream = new Stream(['codec_type' => 'video', 'width' => 960, 'height' => 720, 'sample_aspect_ratio' => $invalidRatio, 'display_aspect_ratio' => '16:9']);
        $this->assertEquals(new Dimension(960, 720), $stream->getDimensions());
    }

    public function provideInvalidRatios(): array
    {
        return [['0:1'], ['2:1:3']];
    }

    public function providePropertiesForDimensionsExtraction(): array
    {
        return [
            [
                ['width' => '960', 'height' => '720',
                    'sar' => '4:3', 'dar' => '16:9',
                    'result_width' => '1280', 'result_height' => '720'],
            ],
            [
                ['width' => '1920', 'height' => '1080',
                    'sar' => '1:1', 'dar' => '16:9',
                    'result_width' => '1920', 'result_height' => '1080'],
            ],
            [
                ['width' => '640', 'height' => '480',
                    'sar' => '75:74', 'dar' => '50:37',
                    'result_width' => '649', 'result_height' => '480'],
            ],
            [
                ['width' => '720', 'height' => '576',
                    'sar' => '52:28', 'dar' => '16:9',
                    'result_width' => '1337', 'result_height' => '752'],
            ],
        ];
    }
}
