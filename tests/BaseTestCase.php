<?php

namespace Tests\FFMpeg;

use PHPUnit\Framework\TestCase;

/**
 * This is a BC Layer to support phpunit 4.8 needed for php <= 5.5.
 */
if (class_exists('PHPUnit_Runner_Version')
    && version_compare(\PHPUnit_Runner_Version::id(), '5', '<')
) {
    class BaseTestCase extends TestCase
    {
        public static function assertScalar($value, $message = '')
        {
            self::assertTrue(is_scalar($value), $message);
        }

        public static function assertIsArray($value, $message = '')
        {
            self::assertTrue(is_array($value), $message);
        }

        public static function assertIsInt($value, $message = '')
        {
            self::assertTrue(is_int($value), $message);
        }

        public static function assertIsBool($value, $message = '')
        {
            self::assertTrue(is_bool($value), $message);
        }

        public static function assertIsString($value, $message = '')
        {
            self::assertTrue(is_string($value), $message);
        }

        public function expectException($exception, $message = null)
        {
            $this->setExpectedException($exception, $message);
        }

        public static function assertStringContainsString($needle, $haystack, $message = '')
        {
            self::assertContains($needle, $haystack, $message);
        }

        public static function assertStringNotContainsString($needle, $haystack, $message = '')
        {
            self::assertNotContains($needle, $haystack, $message);
        }
    }
} else {
    class BaseTestCase extends TestCase
    {
        public function assertScalar($value, $message = '')
        {
            $this->assertTrue(is_scalar($value), $message);
        }
    }
}
