<?php

namespace Tests\FFMpeg;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function assertScalar($value, $message = '')
    {
        $this->assertTrue(is_scalar($value), $message);
    }

    /**
     * Can be removed when phpunit 4.8 (<= needed for php 5.5) support is removed.
     */
    public function assertIsArray($value, $message = '')
    {
        $this->assertTrue(is_array($value), $message);
    }

    /**
     * Can be removed when phpunit 4.8 (<= needed for php 5.5) support is removed.
     */
    public function assertIsInt($value, $message = '')
    {
        $this->assertTrue(is_int($value), $message);
    }

    /**
     * Can be removed when phpunit 4.8 (<= needed for php 5.5) support is removed.
     */
    public function assertIsBool($value, $message = '')
    {
        $this->assertTrue(is_bool($value), $message);
    }

    /**
     * Can be removed when phpunit 4.8 (<= needed for php 5.5) support is removed.
     */
    public function assertIsString($value, $message = '')
    {
        $this->assertTrue(is_string($value), $message);
    }

    /**
     * Can be removed when phpunit 4.8 (<= needed for php 5.5) support is removed.
     */
    public function expectException($exception)
    {
        // PHPUnt BC Layer
        if (method_exists(get_parent_class(), 'expectException')) {
            parent::expectException($exception);

            return;
        }

        $this->setExpectedException($exception);
    }
}
