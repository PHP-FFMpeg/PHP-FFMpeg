<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Filters\Frame\FrameFilterInterface;
use FFMpeg\Filters\Frame\FrameFilters;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\TimeCode;

class Frame extends AbstractMediaType
{
    /** @var TimeCode */
    private $timecode;

    public function __construct($pathfile, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $timecode)
    {
        parent::__construct($pathfile, $driver, $ffprobe);
        $this->timecode = $timecode;
    }

    /**
     * {@inheritdoc}
     *
     * @return FrameFilters
     */
    public function filters()
    {
        return new FrameFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Frame
     */
    public function addFilter(FrameFilterInterface $filter)
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * @return TimeCode
     */
    public function getTimeCode()
    {
        return $this->timecode;
    }

    /**
     * Saves the frame in the given filename.
     *
     * Uses the `unaccurate method by default.`
     *
     * @param string $pathfile
     * @param Boolean $accurate
     *
     * @return Frame
     *
     * @throws RuntimeException
     */
    public function saveAs($pathfile, $accurate = false)
    {
        /**
         * might be optimized with http://ffmpeg.org/trac/ffmpeg/wiki/Seeking%20with%20FFmpeg
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        if (!$accurate) {
            $commands = array(
                '-ss', (string) $this->timecode,
                '-i', $this->pathfile,
                '-vframes', '1',
                '-f', 'image2', $pathfile
            );
        } else {
            $commands = array(
                '-i', $this->pathfile,
                '-vframes', '1', '-ss', (string) $this->timecode,
                '-f', 'image2', $pathfile
            );
        }

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save frame', $e->getCode(), $e);
        }

        return $this;
    }
}
