<?php

declare(strict_types=1);

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
use FFMpeg\Format\VideoInterface;
use FFMpeg\Filters\TPriorityFilter;

class ExtractMultipleFramesFilter implements VideoFilterInterface
{
    use TPriorityFilter;

    /** will extract a frame every second */
    const FRAMERATE_EVERY_SEC = '1/1';
    /** will extract a frame every 2 seconds */
    const FRAMERATE_EVERY_2SEC = '1/2';
    /** will extract a frame every 5 seconds */
    const FRAMERATE_EVERY_5SEC = '1/5';
    /** will extract a frame every 10 seconds */
    const FRAMERATE_EVERY_10SEC = '1/10';
    /** will extract a frame every 30 seconds */
    const FRAMERATE_EVERY_30SEC = '1/30';
    /** will extract a frame every minute */
    const FRAMERATE_EVERY_60SEC = '1/60';

    /** @var int */
    private $priority;
    private $frameRate;
    private $destinationFolder;
    private $frameFileType = 'jpg';

    /** @var string[] */
    private static $supportedFrameFileTypes = ['jpg', 'jpeg', 'png'];

    public function __construct($frameRate = self::FRAMERATE_EVERY_SEC, $destinationFolder = __DIR__, $priority = 0)
    {
        $this->priority = $priority;
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

        throw new InvalidArgumentException('Invalid frame file type, use: ' . implode(',', self::$supportedFrameFileTypes));
    }

    /**
     * @inheritDoc
     */
    public function getFrameRate()
    {
        return $this->frameRate;
    }

    /**
     * @inheritDoc
     */
    public function getDestinationFolder()
    {
        return $this->destinationFolder;
    }

    /**
     * @inheritDoc
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
            if (false !== \preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $this->frameRate, $matches)) {
                $operator = $matches[2];

                switch ($operator) {
                    case '/':
                        $nbFramesPerSecond = $matches[1] / $matches[3];
                        break;

                    default:
                        throw new InvalidArgumentException('The frame rate is not a proper division: ' . $this->frameRate);
                        break;
                }
            }

            // Set the number of digits to use in the exported filenames
            $nbImages = ceil($duration * $nbFramesPerSecond);

            switch (true) {
                case $nbImages < 100:
                    $nbDigitsInFileNames = "02";
                    break;
                case $nbImages < 1000:
                    $nbDigitsInFileNames = "03";
                    break;
                default:
                    $nbDigitsInFileNames = "06";
            }

            // Set the parameters
            $commands[] = '-vf';
            $commands[] = 'fps=' . $this->frameRate;
            $commands[] = $this->destinationFolder . 'frame-%' . $nbDigitsInFileNames . 'd.' . $this->frameFileType;
        } catch (RuntimeException $e) {
            throw new RuntimeException('An error occured while extracting the frames: ' . $e->getMessage() . '. The code: ' . $e->getCode());
        }

        return $commands;
    }
}
