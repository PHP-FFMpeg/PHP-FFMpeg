<?php

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
use FFMpeg\Media\MediaTypeInterface;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Video;
use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Psr\Log\LoggerInterface;

class FFMpeg
{
    /** @var FFMpegDriver */
    private $driver;
    /** @var FFProbe */
    private $ffprobe;

    public function __construct(FFMpegDriver $ffmpeg, FFProbe $ffprobe)
    {
        $this->driver = $ffmpeg;
        $this->ffprobe = $ffprobe;
    }

    /**
     * Sets ffprobe
     *
     * @param FFProbe
     *
     * @return FFMpeg
     */
    public function setFFProbe(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }

    /**
     * Gets FFProbe
     *
     * @return FFProbe
     */
    public function getFFProbe()
    {
        return $this->ffprobe;
    }

    /**
     * Sets ffmpeg driver
     *
     * @return FFMpeg
     */
    public function setFFMpegDriver(FFMpegDriver $ffmpeg)
    {
        $this->driver = $ffmpeg;

        return $this;
    }

    /**
     * Gets the ffmpeg driver
     *
     * @return FFMpegDriver
     */
    public function getFFMpegDriver()
    {
        return $this->driver;
    }

    /**
     * Opens a file in order to be processed
     *
     * @param  string                   $pathfile A pathfile
     *
     * @return MediaTypeInterface
     *
     * @throws InvalidArgumentException
     */
    public function open($pathfile)
    {
        if (!file_exists($pathfile)) {
            throw new InvalidArgumentException(sprintf('File %s does not exists', $pathfile));
        }

        $streams = $this->ffprobe->streams($pathfile);

        if (0 < count($streams->videos())) {
            return new Video($pathfile, $this->driver, $this->ffprobe);
        } elseif (0 < count($streams->audios())) {
            return new Audio($pathfile, $this->driver, $this->ffprobe);
        }

        throw new InvalidArgumentException('Unable to detect file format, only audio and video supported');
    }

    /**
     * Creates a new FFMpeg instance
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface $logger
     * @param FFProbe $probe
     *
     * @return FFMpeg
     */
    public static function create($configuration = array(), LoggerInterface $logger = null, FFProbe $probe = null)
    {
        if (null === $logger) {
            $logger = new Logger('ffmpeg');
            $logger->pushHandler(new NullHandler());
        }

        $driver = FFMpegDriver::load(array('avconv', 'ffmpeg'), $logger, $configuration);

        if (null === $probe) {
            $probe = FFProbe::create($logger, null, $configuration);
        }

        return new static($driver, $probe);
    }
}
