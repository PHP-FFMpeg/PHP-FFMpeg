<?php

namespace Tests\FFMpeg\Unit\FFProbe;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\OptionsTester;

class OptionsTesterTest extends TestCase
{
    public function testHasOptionWithOldFFProbe()
    {
        $this->expectException('\FFMpeg\Exception\RuntimeException');
        $this->expectExceptionMessage('Your FFProbe version is too old and does not support `-help` option, please upgrade.');

        $cache = $this->getCacheMock();
        $optionItem = $this->getCacheItemMock();
        $helpItem = $this->getCacheItemMock();

        $cache->expects($this->exactly(2))
            ->method('getItem')
            ->willReturnOnConsecutiveCalls($optionItem, $helpItem);

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
        $optionItem = $this->getCacheItemMock();
        $helpItem = $this->getCacheItemMock();

        $cache->expects($this->exactly(2))
            ->method('getItem')
            ->willReturnOnConsecutiveCalls($optionItem, $helpItem);

        $optionItem->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $helpItem->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->once())
            ->method('command')
            ->with(array('-help', '-loglevel', 'quiet'))
            ->will($this->returnValue($data));

        $helpItem->expects($this->once())
            ->method('set')
            ->with($data);

        $optionItem->expects($this->once())
            ->method('set')
            ->with($isPresent);

        $cache->expects($this->exactly(2))
            ->method('save')
            ->withConsecutive([$helpItem], [$optionItem]);

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
        $optionItem = $this->getCacheItemMock();
        $helpItem = $this->getCacheItemMock();

        $cache->expects($this->exactly(2))
            ->method('getItem')
            ->willReturnOnConsecutiveCalls($optionItem, $helpItem);

        $optionItem->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $helpItem->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $helpItem->expects($this->once())
            ->method('get')
            ->willReturn($data);

        $optionItem->expects($this->once())
            ->method('set')
            ->with($isPresent);

        $cache->expects($this->once())
            ->method('save')
            ->with($optionItem);

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
        $optionItem = $this->getCacheItemMock();

        $cache->expects($this->once())
            ->method('getItem')
            ->willReturn($optionItem);

        $optionItem->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $optionItem->expects($this->once())
            ->method('get')
            ->willReturn($isPresent);

        $ffprobe = $this->getFFProbeDriverMock();
        $ffprobe->expects($this->never())
            ->method('command');

        $tester = new OptionsTester($ffprobe, $cache);
        $this->assertTrue($isPresent === $tester->has($optionName));
    }
}
