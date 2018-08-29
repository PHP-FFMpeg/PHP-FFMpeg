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
use FFMpeg\Media\Video;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;

class ExtractMultipleFramesFilter implements VideoFilterInterface
{

    use TPriorityFilter;

    /**
     * will extract a frame every second
     */
    const FRAMERATE_EVERY_SEC = '1/1';

    /**
     * will extract a frame every 2 seconds
     */
    const FRAMERATE_EVERY_2SEC = '1/2';

    /**
     * will extract a frame every 5 seconds
     */
    const FRAMERATE_EVERY_5SEC = '1/5';

    /**
     * will extract a frame every 10 seconds
     */
    const FRAMERATE_EVERY_10SEC = '1/10';

    /**
     * will extract a frame every 30 seconds
     */
    const FRAMERATE_EVERY_30SEC = '1/30';

    /**
     * will extract a frame every minute
     */
    const FRAMERATE_EVERY_60SEC = '1/60';

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $frameRate;

    /**
     * @var string
     */
    private $destinationFolder;
    private $frameFileType = 'jpg';

    /** @var string[] */
    private static $supportedFrameFileTypes = ['jpg', 'jpeg', 'png'];

    public function __construct(
        string $frameRate = self::FRAMERATE_EVERY_SEC,
        string $destinationFolder = __DIR__,
        int $priority = 0
    ) {
        $this->setPriority($priority);

        $this->frameRate = $frameRate;

        // Make sure that the destination folder has a trailing slash
        $destinationFolder = rtrim($destinationFolder, '/') . '/';
        // Set the destination folder
        $this->destinationFolder = $destinationFolder;
    }

    /**
     * @param string $frameFileType
     * @throws \FFMpeg\Exception\InvalidArgumentException
     * @return ExtractMultipleFramesFilter
     */
    public function setFrameFileType($frameFileType)
    {
        if (in_array($frameFileType, self::$supportedFrameFileTypes)) {
            $this->frameFileType = $frameFileType;
            return $this;
        }

        throw new InvalidArgumentException(
            'Invalid frame file type, use: ' . implode(', ', self::$supportedFrameFileTypes)
        );
    }

    /**
     * Returns the framerate used.
     * One of the FRAMERATE_EVERY_* constants
     *
     * @return string
     */
    public function getFrameRate() : string
    {
        return $this->frameRate;
    }

    /**
     * Returns the folder the frames will be saved to.
     *
     * @return string
     */
    public function getDestinationFolder() : string
    {
        return $this->destinationFolder;
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format) : array
    {
        $commands = [];
        $duration = 0;

        // Get the duration of the video
        foreach ($video->getStreams()->getVideoStreams() as $stream) {
            if ($stream->has('duration')) {
                $duration = $stream->get('duration');
                break;
            }
        }

        // Get the number of frames per second we have to extract.
        if (preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $this->frameRate, $matches) !== false) {
            $operator = $matches[2];

            switch ($operator) {
                case '/':
                    $nbFramesPerSecond = $matches[1] / $matches[3];
                    break;

                default:
                    throw new InvalidArgumentException('The frame rate is not a proper division: ' . $this->frameRate);
            }
        }

        // Set the number of digits to use in the exported filenames
        $nbImages = ceil($duration * $nbFramesPerSecond);

        if ($nbImages < 100) {
            $nbDigitsInFileNames = "02";
        } elseif ($nbImages < 1000) {
            $nbDigitsInFileNames = "03";
        } else {
            $nbDigitsInFileNames = "06";
        }

            // Set the parameters
        $commands[] = '-vf';
        $commands[] = 'fps=' . $this->frameRate;
        $commands[] = $this->destinationFolder . 'frame-%' . $nbDigitsInFileNames . 'd.' . $this->frameFileType;

        return $commands;
    }
}
