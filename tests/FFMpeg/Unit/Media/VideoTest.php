<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Exception\RuntimeException;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Video;
use Mockery;

class VideoTest extends AbstractStreamableTestCase
{
    public function testFiltersReturnsVideoFilters()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\Video\VideoFilters', $video->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->setFiltersCollection($filters);

        $filter = $this->getMockBuilder('FFMpeg\Filters\Video\VideoFilterInterface')->getMock();

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $video->addFilter($filter);
    }

    public function testAddAudioFilterAddsAFilter()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->setFiltersCollection($filters);

        $filter = $this->getMockBuilder('FFMpeg\Filters\Audio\AudioFilterInterface')->getMock();

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $video->addFilter($filter);
    }

    public function testFrameShouldReturnAFrame()
    {
        $driver  = $this->getFFMpegDriverMock();
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
        $driver         = $this->getFFMpegDriverMock();
        $ffprobe        = $this->getFFProbeMock();
        $outputPathfile = '/target/file';
        $format         = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));

        $configuration = $this->getMockBuilder('Alchemy\BinaryDriver\ConfigurationInterface')->getMock();

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $failure = new RuntimeException('failed to encode');
        $driver->expects($this->once())
            ->method('command')
            ->will($this->throwException($failure));

        $video = new Video(__FILE__, $driver, $ffprobe);
        $this->expectException('\FFMpeg\Exception\RuntimeException');
        $video->save($format, $outputPathfile);
    }

    public function testSaveAppliesFilters()
    {
        $driver         = $this->getFFMpegDriverMock();
        $ffprobe        = $this->getFFProbeMock();
        $outputPathfile = '/target/file';
        $format         = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $configuration = $this->getMockBuilder('Alchemy\BinaryDriver\ConfigurationInterface')->getMock();

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $video = new Video(__FILE__, $driver, $ffprobe);

        $filter = $this->getMockBuilder('FFMpeg\Filters\Video\VideoFilterInterface')->getMock();
        $filter->expects($this->once())
            ->method('apply')
            ->with($video, $format)
            ->will($this->returnValue(['extra-filter-command']));

        $capturedCommands = [];

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
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $configuration = Mockery::mock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->shouldReceive('has')
            ->once()
            ->with('ffmpeg.threads')
            ->andReturn($threads);

        $configuration->shouldReceive('get')
            ->once()
            ->with('temporary_directory')
            ->andReturnNull();

        if ($threads) {
            $configuration->shouldReceive('get')
                ->once()
                ->with('ffmpeg.threads')
                ->andReturn(24);
        }

        $capturedCommands  = [];
        $capturedListeners = null;

        $driver->expects($this->exactly(count($expectedCommands)))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands, &$capturedListeners) {
                $capturedCommands[] = $commands;
                $capturedListeners = $listeners;
            }));

        $outputPathfile = '/target/file';

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->save($format, $outputPathfile);

        foreach ($capturedCommands as $passKey => $pass) {
            $prefix = null;
            if (count($expectedCommands) > 1) {
                // look for pass commands only in multipass cases
                foreach ($pass as $command) {
                    $prefix = null;
                    if (false !== strpos($command, '/pass-')) {
                        $prefix = $command;
                        break;
                    }
                }

                if (null === $prefix) {
                    $this->fail('Unable to find pass prefix command.');
                }
            }

            $found = false || (null === $prefix);
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
        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $format->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(663));
        $format->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $format->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));
        $format->expects($this->any())
            ->method('getAdditionalParameters')
            ->will($this->returnValue(['foo', 'bar']));

        $format2 = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format2->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $format2->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(663));
        $format2->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $format2->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $format2->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));
        $format2->expects($this->any())
            ->method('getAdditionalParameters')
            ->will($this->returnValue(['foo', 'bar']));

        $audioFormat = $this->getMockBuilder('FFMpeg\Format\AudioInterface')->getMock();
        $audioFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $audioFormat->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $audioFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $audioFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $audioFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));

        $audioVideoFormat = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $audioVideoFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
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
        $audioVideoFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $audioVideoFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));
        $audioVideoFormat->expects($this->any())
            ->method('getAdditionalParameters')
            ->will($this->returnValue([]));

        $audioVideoFormatSinglePass = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getVideoCodec')
            ->will($this->returnValue('gloubi-boulga-video'));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(664));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAdditionalParameters')
            ->will($this->returnValue([]));

        $formatExtra = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $formatExtra->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(['extra', 'param']));
        $formatExtra->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(665));
        $formatExtra->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $formatExtra->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $formatExtra->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));
        $formatExtra->expects($this->any())
            ->method('getAdditionalParameters')
            ->will($this->returnValue([]));

        $formatExtra2 = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $formatExtra2->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(['extra', 'param']));
        $formatExtra2->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(665));
        $formatExtra2->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $formatExtra2->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $formatExtra2->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));
        $formatExtra2->expects($this->any())
            ->method('getAdditionalParameters')
            ->will($this->returnValue([]));

        $listeners = [$this->getMockBuilder('Alchemy\BinaryDriver\Listeners\ListenerInterface')->getMock()];

        $progressableFormat = $this->getMockBuilder('Tests\FFMpeg\Unit\Media\Prog')
            ->disableOriginalConstructor()->getMock();
        $progressableFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $progressableFormat->expects($this->any())
            ->method('createProgressListener')
            ->will($this->returnValue($listeners));
        $progressableFormat->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(666));
        $progressableFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $progressableFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $progressableFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $progressableFormat2 = $this->getMockBuilder('Tests\FFMpeg\Unit\Media\Prog')
            ->disableOriginalConstructor()->getMock();
        $progressableFormat2->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $progressableFormat2->expects($this->any())
            ->method('createProgressListener')
            ->will($this->returnValue($listeners));
        $progressableFormat2->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(666));
        $progressableFormat2->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $progressableFormat2->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $progressableFormat2->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $progressableAudioFormat = $this->getMockBuilder('Tests\FFMpeg\Unit\Media\AudioProg')
            ->disableOriginalConstructor()->getMock();
        $progressableAudioFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue([]));
        $progressableAudioFormat->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $progressableAudioFormat->expects($this->any())
            ->method('createProgressListener')
            ->will($this->returnValue($listeners));
        $progressableAudioFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $progressableAudioFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $progressableAudioFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));

        return [
            [false, [[
                '-y', '-i', __FILE__, '-b:v', '663k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', 2, 'foo', 'bar', '-pass', 1, '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                '-b:v', '663k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', 2, 'foo', 'bar', '-pass', 2, '-passlogfile',
                '/target/file',
            ]], null, $format],
            [false, [[
                '-y', '-i', __FILE__,
                '-vcodec', 'gloubi-boulga-video',
                '-acodec', 'patati-patata-audio', '-b:v', '664k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                '-vcodec', 'gloubi-boulga-video',
                '-acodec', 'patati-patata-audio',
                '-b:v', '664k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                '/target/file',
            ]], null, $audioVideoFormat],
            [false, [[
                '-y', '-i', __FILE__,
                '-vcodec', 'gloubi-boulga-video',
                '-acodec', 'patati-patata-audio', '-b:v', '664k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2',
                '/target/file',
            ]], null, $audioVideoFormatSinglePass],
            [false, [[
                '-y', '-i', __FILE__,
                'extra', 'param', '-b:v', '665k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                'extra', 'param', '-b:v', '665k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                '/target/file',
            ]], null, $formatExtra],
            [true, [[
                '-y', '-i', __FILE__,
                '-threads', 24, '-b:v', '663k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', 2, 'foo', 'bar', '-pass', 1, '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                '-threads', 24,
                '-b:v', '663k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', 2, 'foo', 'bar', '-pass', 2, '-passlogfile',
                '/target/file',
            ]], null, $format2],
            [true, [[
                '-y', '-i', __FILE__,
                'extra', 'param', '-threads', 24, '-b:v', '665k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                'extra', 'param', '-threads', 24, '-b:v', '665k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                '/target/file',
            ]], null, $formatExtra2],
            [false, [[
                '-y', '-i', __FILE__, '-b:v', '666k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                '-b:v', '666k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                '/target/file',
            ]], $listeners, $progressableFormat2],
            [true, [[
                '-y', '-i', __FILE__,
                '-threads', 24, '-b:v', '666k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                '/target/file',
            ], [
                '-y', '-i', __FILE__,
                '-threads', 24,
                '-b:v', '666k',
                '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                '/target/file',
            ]], $listeners, $progressableFormat],
            [true, [[
                '-y', '-i', __FILE__,
                '-threads', 24, '-acodec', 'patati-patata-audio',
                '-b:a', '92k', '-ac', '2',
                '/target/file',
            ]], null, $audioFormat],
            [true, [[
                '-y', '-i', __FILE__,
                '-threads', 24, '-acodec', 'patati-patata-audio',
                '-b:a', '92k', '-ac', '2',
                '/target/file',
            ]], $listeners, $progressableAudioFormat],
        ];
    }

    public function testSaveShouldNotStoreCodecFiltersInTheMedia()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $configuration = Mockery::mock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->shouldReceive('has')
            ->with('ffmpeg.threads')
            ->andReturn(true);

        $configuration->shouldReceive('get')
            ->once()
            ->with('ffmpeg.threads')
            ->andReturn(24);

        $configuration->shouldReceive('get')
            ->once()
            ->with('temporary_directory')
            ->andReturnNull();

        $capturedCommands = [];

        $driver->expects($this->exactly(4))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands, &$capturedListeners) {
                $capturedCommands[] = $commands;
            }));

        $outputPathfile = '/target/file';

        $format = $this->getMockBuilder('FFMpeg\Format\VideoInterface')->getMock();
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(['param']));
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->save($format, $outputPathfile);
        $video->save($format, $outputPathfile);

        $expectedPass1 = [
            '-y', '-i', __FILE__, 'param', '-threads', 24, '-b:v', 'k', '-refs',
            '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
            '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71',
            '-qcomp', '0.6', '-qdiff', '4', '-trellis', '1',
            '-pass', '1', '-passlogfile', '/target/file',
        ];
        $expectedPass2 = [
            '-y', '-i', __FILE__, 'param', '-threads', 24, '-b:v', 'k', '-refs',
            '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
            '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71',
            '-qcomp', '0.6', '-qdiff', '4', '-trellis', '1',
            '-pass', '2', '-passlogfile', '/target/file',
        ];

        $n = 1;
        foreach ($capturedCommands as $capturedCommand) {
            $prefix = null;
            foreach ($capturedCommand as $command) {
                if (false !== strpos($command, '/pass-')) {
                    $prefix = $command;
                    break;
                }
            }

            if (null === $prefix) {
                $this->fail('Unable to find pass prefix command.');
            }

            $found = false;
            foreach ($capturedCommand as $key => $command) {
                if ($command === $prefix) {
                    $found = true;
                    unset($capturedCommand[$key]);
                    $capturedCommand = array_values($capturedCommand);
                    break;
                }
            }

            if (!$found) {
                $this->fail('Unable to find pass prefix command back.');
            }

            if (0 === $n % 2) {
                $this->assertEquals($expectedPass2, $capturedCommand);
            } else {
                $this->assertEquals($expectedPass1, $capturedCommand);
            }
            ++$n;
        }
    }

    public function testCaseWhereKiloBitRateIsEqualToZero()
    {
        $driver  = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $pathfile       = '/target/destination';
        $outputPathfile = '/target/file';

        $format = new X264();
        $format->setKiloBitrate(0);

        $configuration = $this->getMockBuilder('Alchemy\BinaryDriver\ConfigurationInterface')->getMock();

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $self = $this;

        $driver->expects($this->exactly(1))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use ($self) {
                $self->assertTrue(!in_array('-b:v', $commands));
            }));

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->save($format, $outputPathfile);
    }

    public function getClassName()
    {
        return 'FFMpeg\Media\Video';
    }
}
