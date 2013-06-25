<?php

namespace FFMpeg\Tests\Media;

use FFMpeg\Tests\TestCase;

abstract class AbstractMediaTestCase extends TestCase
{
    abstract public function testWithInvalidFile();
}
