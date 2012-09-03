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
        $this->object->setProber($this->probe);
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
        $format = new Format\Video\WebM();
        $format-> setDimensions(32, 32);
        $this->object->encode($format, './invalid.file');
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @expectedException FFMpeg\Exception\BinaryNotFoundException
     */
    public function testWrongBinary()
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        $ffmpeg = new FFMpeg('wrongbinary', $logger);
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

        $format = new Format\Video\WebM();
        $format-> setDimensions(32, 32);

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode($format, $dest);

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

        $format = new Format\Video\Ogg();
        $format->setDimensions(32, 32);

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode($format, $dest);

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

        $format = new Format\Video\WebM();
        $format-> setDimensions(32, 32);

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode($format, $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::getMultiple
     */
    public function testGetMultiple()
    {
        $object = FFMpegTester::load($this->logger);
        $this->assertEquals(320, $object->getMultipleTester(321, 16));
        $this->assertEquals(320, $object->getMultipleTester(319, 16));
        $this->assertEquals(320, $object->getMultipleTester(313, 16));
        $this->assertEquals(304, $object->getMultipleTester(312, 16));
        $this->assertEquals(336, $object->getMultipleTester(329, 16));
        $this->assertEquals(16, $object->getMultipleTester(8, 16));
    }
}

class FFMpegTester extends FFMpeg
{
    public function getMultipleTester($value, $multiple)
    {
        return parent::getMultiple($value, $multiple);
    }
}
