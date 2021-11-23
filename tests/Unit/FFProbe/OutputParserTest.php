<?php

namespace Tests\FFMpeg\Unit\FFProbe;

use FFMpeg\FFProbe;
use FFMpeg\FFProbe\OutputParser;
use Tests\FFMpeg\Unit\TestCase;

class OutputParserTest extends TestCase
{
    /**
     * @dataProvider provideTypeDataAndOutput
     */
    public function testParse($type, $data, $expectedOutput)
    {
        $parser = new OutputParser();
        $this->assertEquals($expectedOutput, $parser->parse($type, $data));
    }

    public function testParseWithInvalidArgument()
    {
        $this->expectException('\FFMpeg\Exception\InvalidArgumentException');
        $parser = new OutputParser();
        $parser->parse('comme ca', 'data');
    }

    public function provideTypeDataAndOutput()
    {
        $expectedFormat = json_decode(file_get_contents(__DIR__.'/../../fixtures/ffprobe/show_format.json'), true);
        $expectedStreams = json_decode(file_get_contents(__DIR__.'/../../fixtures/ffprobe/show_streams.json'), true);

        $rawFormat = file_get_contents(__DIR__.'/../../fixtures/ffprobe/show_format.raw');
        $rawStreams = file_get_contents(__DIR__.'/../../fixtures/ffprobe/show_streams.raw');

        return [
            [FFProbe::TYPE_FORMAT, $rawFormat, $expectedFormat],
            [FFProbe::TYPE_STREAMS, $rawStreams, $expectedStreams],
        ];
    }
}
