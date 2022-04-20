<?php

namespace Alchemy\Tests\BinaryDriver\Listeners;

use Alchemy\BinaryDriver\Listeners\DebugListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class DebugListenerTest extends TestCase
{
    public function testHandle()
    {
        $listener = new DebugListener();

        $lines = [];
        $listener->on('debug', function ($line) use (&$lines) {
            $lines[] = $line;
        });
        $listener->handle(Process::ERR, "first line\nsecond line");
        $listener->handle(Process::OUT, "cool output");
        $listener->handle('unknown', "lalala");
        $listener->handle(Process::OUT, "another output\n");

        $expected = [
            '[ERROR] first line',
            '[ERROR] second line',
            '[OUT] cool output',
            '[OUT] another output',
            '[OUT] ',
        ];

        $this->assertEquals($expected, $lines);
    }
}
