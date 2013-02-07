<?php
use FFMpeg\Helper\AudioProgressHelper;
use FFMpeg\FFProbe;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Helper\VideoProgressHelper;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class ProgressTest extends \PHPUnit_Framework_TestCase
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
     * @covers FFMpeg\Helper\ProgressHelper::parseProgress
     * @covers FFMpeg\Helper\ProgressHelper::convertDuration
     * @covers FFMpeg\Helper\ProgressHelper::getProgressInfo
     * @covers FFMpeg\Helper\AudioProgressHelper::getPattern
     */
    public function testProgressHelper()
    {
        $progressInfo = array();

        $audioProgress = new AudioProgressHelper(function($percent, $remaining, $rate) use ($progressInfo ) {
            $progressInfo[] = $percent;
        });

        $dest = __DIR__ . '/../../../files/encode_test.mp3';

        $this->object->open(__DIR__ . '/../../../files/Audio.mp3');
        $this->object->attachHelper($audioProgress);
        $this->object->encode(new Mp3(), $dest);

        $this->assertGreaterThanOrEqual(3, $progressInfo);
    }

    /**
     * @covers FFMpeg\Helper\AudioProgressHelper::getPattern
     */
    public function testAudioProgressHelper()
    {
        $audioProgress = new AudioProgressHelper(function($percent, $remaining, $rate) { });
        $audioProgress->setDuration(500);

        $line = "size=     712kB time=00:00:45.50 bitrate= 128.1kbits/s";
        $audioProgress->parseProgress($line);

        sleep(1);

        $line = "size=     4712kB time=00:01:45.50 bitrate= 128.1kbits/s";
        $progress = $audioProgress->parseProgress($line);

        $this->assertEquals('21.0', $progress['percent']);
    }

    /**
     * @covers FFMpeg\Helper\VideoProgressHelper::getPattern
     */
    public function testVideoProgress()
    {
        $videoProgress = new VideoProgressHelper(function($percent, $remaining, $rate) {});
        $videoProgress->setDuration(500);

        $line = "frame=  206 fps=202 q=10.0 size=     571kB time=00:00:07.12 bitrate= 656.8kbits/s dup=9 drop=0";
        $videoProgress->parseProgress($line);

        sleep(1);

        $line = "frame=  854 fps=113 q=20.0 size=    4430kB time=00:00:33.04 bitrate=1098.5kbits/s dup=36 drop=0";
        $progress = $videoProgress->parseProgress($line);

        $this->assertEquals('6.0', $progress['percent']);
    }
}
