<?php
use FFMpeg\Progress\AudioProgress;

class ProgressTest extends \PHPUnit_Framework_TestCase
{
    protected $format;

    public function setUp()
    {
        $this->format = array(
                "nb_streams" => 1,
                "format_name" => "mp3",
                "format_long_name" => "MPEG audio layer 2/3",
                "start_time" => 0.000000,
                "duration" => 901.155750,
                "size" => 21627738,
                "bit_rate" => 192000
        );
    }


    /**
     * @covers FFMpeg\Progress\Progress::parseProgress
     * @covers FFMpeg\Progress\Progress::convertDuration
     * @covers FFMpeg\Progress\Progress::getProgressInfo
     * @covers FFMpeg\Progress\Progress::microtimeFloat
     * @covers FFMpeg\Progress\AudioProgress::getPattern
     */
    public function testAudioProgress()
    {
        $audioProgress = new AudioProgress($this->format);

        $line = "size=     712kB time=00:00:45.50 bitrate= 128.1kbits/s";
        $audioProgress->parseProgress($line);

        sleep(1);

        $line = "size=     4712kB time=00:01:45.50 bitrate= 128.1kbits/s";
        $progress = $audioProgress->parseProgress($line);

        $rate = explode(' ', $progress['rate']);
        $this->assertEquals('11%', $progress['percent']);
        $this->assertGreaterThanOrEqual(4.0, $progress['remaining']);
        $this->assertGreaterThanOrEqual(4000, $rate[0]);
    }

    /**
     * @covers FFMpeg\Progress\VideoProgress::getPattern
     */
    public function testVideoProgress()
    {
        $audioProgress = new AudioProgress($this->format);

        $line = "frame=  206 fps=202 q=10.0 size=     571kB time=00:00:07.12 bitrate= 656.8kbits/s dup=9 drop=0";
        $audioProgress->parseProgress($line);

        sleep(1);

        $line = "frame=  854 fps=113 q=20.0 size=    4430kB time=00:00:33.04 bitrate=1098.5kbits/s dup=36 drop=0";
        $progress = $audioProgress->parseProgress($line);

        $rate = explode(' ', $progress['rate']);
        $this->assertEquals('3%', $progress['percent']);
        $this->assertGreaterThanOrEqual(5.0, $progress['remaining']);
        $this->assertGreaterThanOrEqual(3860, $rate[0]);
    }
}
