<?php
declare (strict_types = 1);
/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use Alchemy\BinaryDriver\ConfigurationInterface;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Video;
use Psr\Log\LoggerInterface;

/**
 * Main class for opening media files to manipulate or read data out of them.
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2018
 * @license     MIT License
 * @package     FFMpeg
 */
class FFMpeg
{

    /**
     * @var FFMpegDriver
     */
    private $driver;

    /**
     * @var FFProbe
     */
    private $ffprobe;

    public function __construct(FFMpegDriver $ffmpeg, FFProbe $ffprobe)
    {
        $this->driver = $ffmpeg;
        $this->ffprobe = $ffprobe;
    }

    /**
     * Sets FFProbe.
     *
     * @param  FFProbe
     * @return FFMpeg
     */
    public function setFFProbe(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }

    /**
     * Gets FFProbe.
     *
     * @return FFProbe
     */
    public function getFFProbe() : FFProbe
    {
        return $this->ffprobe;
    }

    /**
     * Sets the ffmpeg driver.
     *
     * @return FFMpeg
     */
    public function setFFMpegDriver(FFMpegDriver $ffmpeg)
    {
        $this->driver = $ffmpeg;

        return $this;
    }

    /**
     * Gets the ffmpeg driver.
     *
     * @return FFMpegDriver
     */
    public function getFFMpegDriver() : FFMpegDriver
    {
        return $this->driver;
    }

    /**
     * Opens a file in order to be processed.
     *
     * @param  string $pathfile A path to a file
     * @return Audio|Video
     * @throws InvalidArgumentException when it fails to detect the file format or there is a general error.
     */
    public function open(string $pathfile) : Audio
    {
        if (null === ($streams = $this->ffprobe->streams($pathfile))) {
            throw new RuntimeException(sprintf('Unable to probe "%s".', $pathfile));
        }

        if (count($streams->getVideoStreams()) >= 1) {
            // media is more likely a video file
            return new Video($pathfile, $this->driver, $this->ffprobe);
        } elseif (count($streams->getAudioStreams()) >= 1) {
            // media is more likely an audio file
            return new Audio($pathfile, $this->driver, $this->ffprobe);
        }

        throw new InvalidArgumentException('Unable to detect file format, only audio and video are supported.');
    }

    /**
     * Creates a new FFMpeg instance.
     *
     * @param  array|ConfigurationInterface $configuration
     * @param  LoggerInterface              $logger
     * @param  FFProbe                      $probe
     * @return FFMpeg
     */
    public static function create($configuration = [], LoggerInterface $logger = null, FFProbe $probe = null) : FFMpeg
    {
        if (null === $probe) {
            $probe = FFProbe::create($configuration, $logger, null);
        }

        return new static(FFMpegDriver::create($logger, $configuration), $probe);
    }
}
