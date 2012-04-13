<?php

namespace FFMpeg;

class FFMpegTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FFMpeg
     */
    protected $object;

    /**
     * @covers FFMpeg\FFMpeg::open
     * @todo Implement testOpen().
     */
    public function testOpen()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers FFMpeg\FFMpeg::extractImage
     * @todo Implement testExtractImage().
     */
    public function testExtractImage()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     */
    public function testEncodeWebm()
    {
        $ffprobe = FFProbe::load();

        $dest = __DIR__ . '/../../files/encode_test.webm';

        $ffmpeg = FFMpeg::load(new \Monolog\Logger('test'));
        $ffmpeg->open(__DIR__ . '/../../files/Test.ogv');
        $ffmpeg->encode(new Format\WebM(32, 32), $dest);

        $ffprobe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     */
    public function testEncodeOgg()
    {
        $ffprobe = FFProbe::load();

        $dest = __DIR__ . '/../../files/encode_test.ogv';

        $ffmpeg = FFMpeg::load(new \Monolog\Logger('test'));
        $ffmpeg->open(__DIR__ . '/../../files/Test.ogv');
        $ffmpeg->encode(new Format\Ogg(32, 32), $dest);

        $ffprobe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     */
    public function testEncodeX264()
    {

        $ffprobe = FFProbe::load();

        $dest = __DIR__ . '/../../files/encode_test.mp4';

        $ffmpeg = FFMpeg::load(new \Monolog\Logger('test'));
        $ffmpeg->open(__DIR__ . '/../../files/Test.ogv');
        $ffmpeg->encode(new Format\X264(32, 32), $dest);

        $ffprobe->probeFormat($dest);

        unlink($dest);
    }

}
