<?php

namespace FFMpeg\Tests\Media;

use FFMpeg\Media\Video;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\VideoInterface;

class VideoTest extends AbstractStreamableTestCase
{
    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testWithInvalidFile()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        new Video('/no/file', $driver, $ffprobe);
    }

    public function testFiltersReturnsVideoFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\Video\VideoFilters', $video->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->setFiltersCollection($filters);

        $filter = $this->getMock('FFMpeg\Filters\Video\VideoFilterInterface');

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $video->addFilter($filter);
    }

    public function testFrameShouldReturnAFrame()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $at = $this->getTimeCodeMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $frame = $video->frame($at);

        $this->assertInstanceOf('FFMpeg\Media\Frame', $frame);
        $this->assertSame($at, $frame->getTimeCode());
        $this->assertSame(__FILE__, $frame->getPathfile());
    }

    public function testSaveWithFailure()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $outputPathfile = '/target/file';
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $failure = new ExecutionFailureException('failed to encode');
        $driver->expects($this->once())
            ->method('command')
            ->will($this->throwException($failure));

        $video = new Video(__FILE__, $driver, $ffprobe);
        $this->setExpectedException('FFMpeg\Exception\RuntimeException');
        $video->save($format, $outputPathfile);
    }

    public function testSaveAppliesFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $outputPathfile = '/target/file';
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $video = new Video(__FILE__, $driver, $ffprobe);

        $filter = $this->getMock('FFMpeg\Filters\Video\VideoFilterInterface');
        $filter->expects($this->once())
            ->method('apply')
            ->with($video, $format)
            ->will($this->returnValue(array('extra-filter-command')));

        $capturedCommands = array();

        $driver->expects($this->exactly(2))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands) {
                $capturedCommands[] = $commands;
            }));

        $video->addFilter($filter);
        $video->save($format, $outputPathfile);

        foreach ($capturedCommands as $commands) {
            $this->assertEquals('-y', $commands[0]);
            $this->assertEquals('-i', $commands[1]);
            $this->assertEquals(__FILE__, $commands[2]);
            $this->assertEquals('extra-filter-command', $commands[3]);
        }
    }

    /**
     * @dataProvider provideSaveData
     */
    public function testSaveShouldSave($threads, $expectedCommands, $expectedListeners, $format)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->expects($this->once())
            ->method('has')
            ->with($this->equalTo('ffmpeg.threads'))
            ->will($this->returnValue($threads));

        if ($threads) {
            $configuration->expects($this->once())
                ->method('get')
                ->with($this->equalTo('ffmpeg.threads'))
                ->will($this->returnValue(24));
        } else {
            $configuration->expects($this->never())
                ->method('get');
        }

        $capturedCommands = array();
        $capturedListeners = null;

        $driver->expects($this->exactly(2))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands, &$capturedListeners) {
                $capturedCommands[] = $commands;
                $capturedListeners = $listeners;
            }));

        $outputPathfile = '/target/file';

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->save($format, $outputPathfile);

        $prefix = null;

        foreach ($capturedCommands as $passKey => $pass) {
            foreach ($pass as $command) {
                if (0 === strpos($command, 'pass-')) {
                    $prefix = $command;
                    break;
                }
            }

            if (null === $prefix) {
                $this->fail('Unable to find pass prefix command.');
            }

            $found = false;
            foreach ($pass as $key => $command) {
                if ($command === $prefix) {
                    $found = true;
                    unset($capturedCommands[$passKey][$key]);
                    $capturedCommands[$passKey] = array_values($capturedCommands[$passKey]);
                    break;
                }
            }

            if (!$found) {
                $this->fail('Unable to find pass prefix command back.');
            }
        }

        $this->assertEquals($expectedCommands, $capturedCommands);
        $this->assertEquals($expectedListeners, $capturedListeners);
    }

    public function provideSaveData()
    {
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $format->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(663));
        $format->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));

        $audioVideoFormat = $this->getMock('FFMpeg\Format\VideoInterface');
        $audioVideoFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $audioVideoFormat->expects($this->any())
            ->method('getVideoCodec')
            ->will($this->returnValue('gloubi-boulga-video'));
        $audioVideoFormat->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $audioVideoFormat->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(664));
        $audioVideoFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));

        $formatExtra = $this->getMock('FFMpeg\Format\VideoInterface');
        $formatExtra->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array('extra', 'param')));
        $formatExtra->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(665));
        $formatExtra->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));

        $listeners = array($this->getMock('Alchemy\BinaryDriver\Listeners\ListenerInterface'));

        $progressableFormat = $this->getMockBuilder('FFMpeg\Tests\Media\Prog')
            ->disableOriginalConstructor()->getMock();
        $progressableFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $progressableFormat->expects($this->any())
            ->method('createProgressListener')
            ->will($this->returnValue($listeners));
        $progressableFormat->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(666));
        $progressableFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));

        return array(
            array(false, array(array(
                    '-y', '-i', __FILE__, '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), null, $format),
            array(false, array(array(
                    '-y', '-i', __FILE__,
                    '-vcodec', 'gloubi-boulga-video',
                    '-acodec', 'patati-patata-audio', '-b:v', '664k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-vcodec', 'gloubi-boulga-video',
                    '-acodec', 'patati-patata-audio',
                    '-b:v', '664k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), null, $audioVideoFormat),
            array(false, array(array(
                    '-y', '-i', __FILE__,
                    'extra', 'param','-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    'extra', 'param', '-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), null, $formatExtra),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    '-threads', 24, '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-threads', 24,
                    '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), null, $format),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    'extra', 'param', '-threads', 24, '-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    'extra', 'param', '-threads', 24, '-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), null, $formatExtra),
            array(false, array(array(
                    '-y', '-i', __FILE__, '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), $listeners, $progressableFormat),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    '-threads', 24, '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '1', '-passlogfile',
                    '-an', '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-threads', 24,
                    '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-pass', '2', '-passlogfile',
                    '-ac', '2', '-ar', '44100', '/target/file',
                )), $listeners, $progressableFormat),
        );
    }

    public function getClassName()
    {
        return 'FFMpeg\Media\Video';
    }
}

abstract class Prog implements ProgressableInterface, VideoInterface
{
}
