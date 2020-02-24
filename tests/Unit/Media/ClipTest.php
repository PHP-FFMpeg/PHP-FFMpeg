<?php
namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Clip;

class ClipTest extends AbstractMediaTestCase
{

    /**
     * @dataProvider provideBuildOptions
     */
    public function testBuildCommand($startValue, $durationValue, $commands)
    {
        $configuration = $this->getConfigurationMock();

        $driver = $this->getFFMpegDriverMock();
        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $ffprobe = $this->getFFProbeMock();

        $start = $this->getTimeCodeMock();
        $start->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue($startValue));

        $duration = null;
        if (null !== $durationValue) {
            $duration = $this->getTimeCodeMock();
            $duration->expects($this->once())
                ->method('__toString')
                ->will($this->returnValue($durationValue));
        }

        $outputPathfile = '/target/file';

        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));

        $clip = new Clip($this->getVideoMock(__FILE__), $driver, $ffprobe, $start, $duration);
        $fc = $clip->getFinalCommand($format, $outputPathfile);

        $this->assertCount(1, $fc);
        $this->assertStringStartsWith(implode(' ', $commands), $fc[0]);
    }

    public function provideBuildOptions()
    {
        return array(
            array('SS01', null, array(
                    '-y', '-ss', 'SS01',
                    '-i', __FILE__)
            ),
            array('SS02', 'D02', array(
                    '-y', '-ss', 'SS02',
                    '-i', __FILE__,
                    '-t', 'D02')
            )
        );
    }
}
