<?php

namespace FFMpeg\Tests\FFProbe\DataMapping;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Tests\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;

class StreamTest extends TestCase
{
    /**
     * @dataProvider provideAudioCases
     */
    public function testIsAudio($isAudio, $properties)
    {
        $stream = new Stream($properties);
        $this->assertTrue($isAudio === $stream->isAudio());
    }

    public function provideAudioCases()
    {
        return array(
            array(true, array('codec_type' => 'audio')),
            array(false, array('codec_type' => 'video')),
        );
    }

    /**
     * @dataProvider provideVideoCases
     */
    public function testIsVideo($isVideo, $properties)
    {
        $stream = new Stream($properties);
        $this->assertTrue($isVideo === $stream->isVideo());
    }

    public function provideVideoCases()
    {
        return array(
            array(true, array('codec_type' => 'video')),
            array(false, array('codec_type' => 'audio')),
        );
    }

    /**
     * @expectedException FFMpeg\Exception\LogicException
     * @expectedExceptionMessage Dimensions can only be retrieved from video streams.
     */
    public function testGetDimensionsFromAudio()
    {
        $stream = new Stream(array('codec_type' => 'audio'));
        $stream->getDimensions();
    }

    public function testGetDimensionsFromVideo()
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960, 'height' => 720));
        $this->assertEquals(new Dimension(960, 720), $stream->getDimensions());
    }

    /**
     * @dataProvider provideInvalidPropertiesForDimensionsExtraction
     * @expectedException FFMpeg\Exception\RuntimeException
     * @expectedExceptionMessage Unable to extract dimensions.
     */
    public function testUnableToGetDimensionsFromVideo($properties)
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960));
        $stream->getDimensions();
    }

    public function provideInvalidPropertiesForDimensionsExtraction()
    {
        return array(
            array('codec_type' => 'video', 'width' => 960),
            array('codec_type' => 'video', 'height' => 960),
        );
    }

    public function testGetDimensionsFromVideoWithDisplayRatio()
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960, 'height' => 720, 'sample_aspect_ratio' => '4:3', 'display_aspect_ratio' => '16:9'));
        $this->assertEquals(new Dimension(1280, 720), $stream->getDimensions());
    }

    public function testGetDimensionsFromVideoWith11SampleRatio()
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 1920, 'height' => 1080, 'sample_aspect_ratio' => '1:1', 'display_aspect_ratio' => '16:9'));
        $this->assertEquals(new Dimension(1920, 1080), $stream->getDimensions());
    }

    /**
     * @dataProvider provideInvalidRatios
     */
    public function testGetDimensionsFromVideoWithInvalidDisplayRatio($invalidRatio)
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960, 'height' => 720, 'sample_aspect_ratio' => $invalidRatio, 'display_aspect_ratio' => '16:9'));
        $this->assertEquals(new Dimension(960, 720), $stream->getDimensions());
    }

    public function provideInvalidRatios()
    {
        return array(array('0:1'), array('2:1:3'));
    }
}
