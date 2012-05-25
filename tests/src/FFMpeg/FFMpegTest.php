<?php

namespace FFMpeg;

use Monolog\Logger;
use Monolog\Handler\NullHandler;

class FFMpegTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FFMpeg
     */
    protected $object;

    /**
     * @var FFProbe
     */
    protected $probe;
    protected $logger;

    public function setUp()
    {
        $this->logger = new Logger('tests');
        $this->logger->pushHandler(new NullHandler());

        $this->object = FFMpeg::load($this->logger);
        $this->probe = FFProbe::load($this->logger);
    }

    /**
     * @covers FFMpeg\FFMpeg::open
     * @expectedException \InvalidArgumentException
     */
    public function testOpenInvalid()
    {
        $this->object->open(__DIR__ . '/invalid.files');
    }

    /**
     * @covers FFMpeg\FFMpeg::open
     */
    public function testOpen()
    {
        $this->object->open(__DIR__ . '/../../files/Test.ogv');
    }

    /**
     * @covers FFMpeg\FFMpeg::extractImage
     */
    public function testExtractImage()
    {
        $dest = __DIR__ . '/../../files/extract_Test.jpg';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->extractImage(2, $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::extractImage
     */
    public function testExtractImagePng()
    {
        $dest = __DIR__ . '/../../files/extract_Test.png';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->extractImage(2, $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::extractImage
     */
    public function testExtractImageGif()
    {
        $dest = __DIR__ . '/../../files/extract_Test.gif';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->extractImage(2, $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::extractImage
     * @expectedException \FFMpeg\Exception\LogicException
     */
    public function testExtractImageNoMovie()
    {
        $this->object->extractImage(2, 'Path');
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @expectedException \FFMpeg\Exception\LogicException
     */
    public function testEncode()
    {
        $this->object->encode(new Format\Video\WebM(32, 32), './invalid.file');
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @expectedException \RuntimeException
     */
    public function testWrongBinary()
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        $ffmpeg = new FFMpeg('wrongbinary', $logger);
        $ffmpeg->open(__DIR__ . '/../../files/Test.ogv');
        $ffmpeg->encode(new Format\Video\WebM(32, 32), './invalid.file');
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @covers FFMpeg\FFMpeg::encodeAudio
     */
    public function testEncodeMp3()
    {
        $dest = __DIR__ . '/../../files/encode_test.mp3';

        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->encode(new Format\Audio\Mp3(), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @covers FFMpeg\FFMpeg::encodeAudio
     */
    public function testEncodeFlac()
    {
        $dest = __DIR__ . '/../../files/encode_test.flac';

        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->encode(new Format\Audio\Flac(), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @covers FFMpeg\FFMpeg::encodeVideo
     */
    public function testEncodeWebm()
    {
        $dest = __DIR__ . '/../../files/encode_test.webm';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode(new Format\Video\WebM(32, 32), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @covers FFMpeg\FFMpeg::encodeVideo
     */
    public function testEncodeOgg()
    {
        $dest = __DIR__ . '/../../files/encode_test.ogv';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode(new Format\Video\Ogg(32, 32), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @covers FFMpeg\FFMpeg::encodeVideo
     */
    public function testEncodeX264()
    {
        $dest = __DIR__ . '/../../files/encode_test.mp4';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode(new Format\Video\X264(32, 32), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

}
