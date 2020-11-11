<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Spectrum;

class SpectrumTest extends AbstractMediaTestCase
{

    /**
     * @dataProvider provideSaveOptions
     */
    public function testSave($commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $pathfile = '/tests/files/Audio.mp3';

        array_push($commands, $pathfile);

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $spectrum = new Spectrum($this->getAudioMock(), $driver, $ffprobe, 640, 120);
        $this->assertSame($spectrum, $spectrum->save($pathfile));
    }

    public function provideSaveOptions()
    {
        return array(
            array(
                array(
                    '-y', '-i', NULL, '-filter_complex',
                    'showspectrumpic=s=640x120',
                    '-frames:v', '1',
                ),
            ),
        );
    }
}
