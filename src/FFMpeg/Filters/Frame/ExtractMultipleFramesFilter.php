<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Frame;

use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Frame;

class ExtractMultipleFramesFilter extends AbstractMediaType implements FrameFilterInterface
{
    /** will extract a frame every second */
    const FRAMERATE_EVERY_SEC = '1/1';
    /** will extract a frame every 2 seconds */
    const FRAMERATE_EVERY_2SEC = '1/2';
    /** will extract a frame every 5 seconds */
    const FRAMERATE_EVERY_5SEC = '1/5';
    /** will extract a frame every 10 seconds */
    const FRAMERATE_EVERY_10SEC = '1/10';
    /** will extract a frame every 30 seconds */
    const FRAMERATE_EVERY_10SEC = '1/30';
    /** will extract a frame every minute */
    const FRAMERATE_EVERY_10SEC = '1/60';
{
    /** @var integer */
    private $priority;
    private $frameRate;
    private $destinationFolder;

    public function __construct($frameRate = self::FRAMERATE_EVERY_SEC, $destinationFolder = __DIR__, $priority = 0)
    {
        $this->priority = $priority;
        $this->frameRate = $frameRate;
        $this->destinationFolder = $destinationFolder;
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
    public function apply(Frame $frame)
    {
        $dimensions = null;
        $commands = array();

        foreach ($frame->getVideo()->getStreams() as $stream) {
            if ($stream->isVideo()) {
                try {
                    // Get the duration of the video
                    $duration = $this->getFormat()->get('duration');

                    // Get the number of frames per second we have to extract.
                    if(preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $this->frameRate, $matches) !== FALSE){
                        $operator = $matches[2];

                        switch($operator){
                            case '/':
                                $nbFramesPerSecond = $matches[1] / $matches[3];
                                break;

                            default:
                                throw new InvalidArgumentException('The frame rate is not a proper division: ' . $this->frameRate);
                                break;
                        }
                    }

                    // Set the number of digits to use in the exported filenames
                    $nbDigitsInFileNames = ceil( $duration * $nbFramesPerSecond );

                    // Set the parameters
                    $commands[] = '-vf';
                    $commands[] = 'fps=' . $this->frameRate;
                    $commands[] = $this->destinationFolder . 'frame-%0'.$nbDigitsInFileNames.'d.jpg';
                    break;
                }
                catch (RuntimeException $e) {

                }
            }
        }

        return $commands;
    }
}
