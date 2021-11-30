<?php

namespace Tests\FFMpeg;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function assertScalar($value, $message = '')
    {
        $this->assertTrue(is_scalar($value), $message);
    }
}
