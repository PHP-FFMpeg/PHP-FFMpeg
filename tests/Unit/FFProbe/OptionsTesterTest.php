<?php

namespace Tests\FFMpeg\Unit\FFProbe;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\OptionsTester;

class OptionsTesterTest extends TestCase
{
    public function testHasOptionWithOldFFProbe()
    {
        $this->expectException(
            '\FFMpeg\Exception\RuntimeException',
            'Your FFProbe version is too old and does not support `-help` option, please upgrade.'
        );
        $cache = $this->getCacheMock();

        $executionFailerExceptionMock = $this->getMockBuilder('Alchemy\BinaryDriver\Exception\ExecutionFailureException')
            ->disableOriginalConstructor()
            ->getMock();

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->once())
            ->method('command')
            ->with(array('-help', '-loglevel', 'quiet'))
            ->will($this->throwException($executionFailerExceptionMock));

        $tester = new OptionsTester($ffprobe, $cache);
        $tester->has('-print_format');
    }

    /**
     * @dataProvider provideOptions
     */
    public function testHasOptionWithCacheEmpty($isPresent, $data, $optionName)
    {
        $cache = $this->getCacheMock();

        $cache->expects($this->never())
            ->method('fetch');

        $cache->expects($this->exactly(2))
            ->method('contains')
            ->will($this->returnValue(false));

        $cache->expects($this->exactly(2))
            ->method('save');

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->once())
            ->method('command')
            ->with(array('-help', '-loglevel', 'quiet'))
            ->will($this->returnValue($data));

        $tester = new OptionsTester($ffprobe, $cache);
        $this->assertTrue($isPresent === $tester->has($optionName));
    }

    public function provideOptions()
    {
        $data = file_get_contents(__DIR__ . '/../../fixtures/ffprobe/help.raw');

        return array(
            array(true, $data, '-print_format'),
            array(false, $data, '-another_print_format'),
        );
    }

    /**
     * @dataProvider provideOptions
     */
    public function testHasOptionWithHelpCacheLoaded($isPresent, $data, $optionName)
    {
        $cache = $this->getCacheMock();

        $cache->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($data));

        $cache->expects($this->exactly(2))
            ->method('contains')
            ->willReturnOnConsecutiveCalls(
                $this->returnValue(false),
                $this->returnValue(true));

        $cache->expects($this->once())
            ->method('save');

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->never())
            ->method('command');

        $tester = new OptionsTester($ffprobe, $cache);
        $this->assertTrue($isPresent === $tester->has($optionName));
    }

    /**
     * @dataProvider provideOptions
     */
    public function testHasOptionWithCacheFullyLoaded($isPresent, $data, $optionName)
    {
        $cache = $this->getCacheMock();

        $cache->expects($this->once())
            ->method('fetch')
            ->with('option-' . $optionName)
            ->will($this->returnValue($isPresent));

        $cache->expects($this->once())
            ->method('contains')
            ->with('option-' . $optionName)
            ->will($this->returnValue(true));

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->never())
            ->method('command');

        $tester = new OptionsTester($ffprobe, $cache);
        $this->assertTrue($isPresent === $tester->has($optionName));
    }
}
