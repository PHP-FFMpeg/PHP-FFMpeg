<?php

namespace Tests\FFMpeg\Unit\FFProbe;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\OptionsTester;

class OptionsTesterTest extends TestCase
{
    /**
     * @expectedException FFMpeg\Exception\RuntimeException
     * @expectedExceptionMessage Your FFProbe version is too old and does not support `-help` option, please upgrade.
     */
    public function testHasOptionWithOldFFProbe()
    {
        $cache = $this->getCacheMock();

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->once())
            ->method('command')
            ->with(array('-help', '-loglevel', 'quiet'))
            ->will($this->throwException(new ExecutionFailureException('Failed to execute')));

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
            ->method('get');

        $cache->expects($this->exactly(2))
            ->method('has')
            ->will($this->returnValue(false));

        $cache->expects($this->exactly(2))
            ->method('set');

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->once())
            ->method('command')
            ->with(['-help', '-loglevel', 'quiet'])
            ->will($this->returnValue($data));

        $tester = new OptionsTester($ffprobe, $cache);
        $this->assertTrue($isPresent === $tester->has($optionName));
    }

    public function provideOptions()
    {
        $data = file_get_contents(__DIR__ . '/../../fixtures/ffprobe/help.raw');

        return [
            [true, $data, '-print_format'],
            [false, $data, '-another_print_format'],
        ];
    }

    /**
     * @dataProvider provideOptions
     */
    public function testHasOptionWithHelpCacheLoaded($isPresent, $data, $optionName)
    {
        $cache = $this->getCacheMock();

        $cache->expects($this->once())
            ->method('get')
            ->will($this->returnValue($data));

        $cache->expects($this->at(0))
            ->method('has')
            ->will($this->returnValue(false));

        $cache->expects($this->at(1))
            ->method('has')
            ->will($this->returnValue(true));

        $cache->expects($this->once())
            ->method('set');

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
            ->method('get')
            ->with('option-' . $optionName)
            ->will($this->returnValue($isPresent));

        $cache->expects($this->once())
            ->method('has')
            ->with('option-' . $optionName)
            ->will($this->returnValue(true));

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->never())
            ->method('command');

        $tester = new OptionsTester($ffprobe, $cache);
        $this->assertTrue($isPresent === $tester->has($optionName));
    }
}
