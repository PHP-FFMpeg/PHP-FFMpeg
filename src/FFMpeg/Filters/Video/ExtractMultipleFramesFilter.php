<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <romain@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class ExtractMultipleFramesFilter implements VideoFilterInterface
{
    /** will extract a frame every second */
    public const FRAMERATE_EVERY_SEC = '1/1';
    /** will extract a frame every 2 seconds */
    public const FRAMERATE_EVERY_2SEC = '1/2';
    /** will extract a frame every 5 seconds */
    public const FRAMERATE_EVERY_5SEC = '1/5';
    /** will extract a frame every 10 seconds */
    public const FRAMERATE_EVERY_10SEC = '1/10';
    /** will extract a frame every 30 seconds */
    public const FRAMERATE_EVERY_30SEC = '1/30';
    /** will extract a frame every minute */
    public const FRAMERATE_EVERY_60SEC = '1/60';

    /** @var int */
    private $priority;
    private $frameRate;
    private $destinationFolder;
    private $frameFileType = 'jpg';

    /** @var array */
    private static $supportedFrameFileTypes = array('jpg', 'jpeg', 'png');

    public function __construct($frameRate = self::FRAMERATE_EVERY_SEC, $destinationFolder = __DIR__, $priority = 0)
    {
        $this->priority = $priority;
        $this->frameRate = $frameRate;

        // Make sure that the destination folder has a trailing slash
        if (0 != strcmp(substr($destinationFolder, -1), '/')) {
            $destinationFolder .= '/';
        }

        // Set the destination folder
        $this->destinationFolder = $destinationFolder;
    }

    /**
     * @param string $frameFileType
     *
     * @throws \FFMpeg\Exception\InvalidArgumentException
     *
     * @return ExtractMultipleFramesFilter
     */
    public function setFrameFileType($frameFileType)
    {
        if (in_array($frameFileType, self::$supportedFrameFileTypes)) {
            $this->frameFileType = $frameFileType;

            return $this;
        }

        throw new InvalidArgumentException('Invalid frame file type, use: '.implode(',', self::$supportedFrameFileTypes));
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getFrameRate()
    {
        return $this->frameRate;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinationFolder()
    {
        return $this->destinationFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $commands = [];
        $duration = 0;

        try {
            // Get the duration of the video
            foreach ($video->getStreams()->videos() as $stream) {
                if ($stream->has('duration')) {
                    $duration = $stream->get('duration');
                }
            }

            // Get the number of frames per second we have to extract.
            if (false !== preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $this->frameRate, $matches)) {
                $operator = $matches[2];

                switch ($operator) {
                    case '/':
                        $nbFramesPerSecond = $matches[1] / $matches[3];
                        break;

                    default:
                        throw new InvalidArgumentException('The frame rate is not a proper division: '.$this->frameRate);
                        break;
                }
            }

            // Set the number of digits to use in the exported filenames
            $nbImages = ceil($duration * $nbFramesPerSecond);

            if ($nbImages < 100) {
                $nbDigitsInFileNames = '02';
            } elseif ($nbImages < 1000) {
                $nbDigitsInFileNames = '03';
            } else {
                $nbDigitsInFileNames = '06';
            }

            // Set the parameters
            $commands[] = '-vf';
            $commands[] = 'fps='.$this->frameRate;
            $commands[] = $this->destinationFolder.'frame-%'.$nbDigitsInFileNames.'d.'.$this->frameFileType;
        } catch (RuntimeException $e) {
            throw new RuntimeException('An error occured while extracting the frames: '.$e->getMessage().'. The code: '.$e->getCode());
        }

        return $commands;
    }
}
