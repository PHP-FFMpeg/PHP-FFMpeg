<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Filters\Gif\GifFilterInterface;
use FFMpeg\Filters\Gif\GifFilters;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;

class Gif extends AbstractMediaType
{
    /** @var TimeCode */
    private $timecode;
    /** @var Dimension */
    private $dimension;
    /** @var integer */
    private $duration;
    /** @var Video */
    private $video;

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $timecode, Dimension $dimension, $duration = null)
    {
        parent::__construct($video->getPathfile(), $driver, $ffprobe);
        $this->timecode = $timecode;
        $this->dimension = $dimension;
        $this->duration = $duration;
        $this->video = $video;
    }

    /**
     * Returns the video related to the gif.
     *
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * {@inheritdoc}
     *
     * @return GifFilters
     */
    public function filters()
    {
        return new GifFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Gif
     */
    public function addFilter(GifFilterInterface $filter)
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
     * @return Dimension
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Saves the gif in the given filename.
     *
     * @param string $pathfile
     *
     * @return Gif
     *
     * @throws RuntimeException
     */
    public function save($pathfile)
    {
        /**
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        $commands = array(
            '-ss', (string)$this->timecode
        );

        if (null !== $this->duration) {
            $commands[] = '-t';
            $commands[] = (string)$this->duration;
        }

        $commands[] = '-i';
        $commands[] = $this->pathfile;
        $commands[] = '-vf';
        $commands[] = 'scale=' . $this->dimension->getWidth() . ':-1';
        $commands[] = '-gifflags';
        $commands[] = '+transdiff';
        $commands[] = '-y';

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        $commands = array_merge($commands, array($pathfile));

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save gif', $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * Saves the gif in the given filename.
     *
     * @param string $pathfile
     * @param string $palette
     *
     * @return Gif
     */
    public function saveWithHighQuality($pathfile, $palette = '/tmp/palette.png')
    {
        /**
         * @see http://blog.pkh.me/p/21-high-quality-gif-with-ffmpeg.html
         */

        $filters = 'fps=30,scale=' . $this->dimension->getWidth() . ':-1:flags=lanczos';
        $commands = $commands_2 = array(
            '-ss', (string)$this->timecode
        );

        if (null !== $this->duration) {
            $commands[] = $commands_2[] = '-t';
            $commands[] = $commands_2[] = (string)$this->duration;
        }

        $commands[] = $commands_2[] = '-i';
        $commands[] = $commands_2[] = $this->pathfile;
        $commands[] = '-vf';
        $commands[] = $filters . ',palettegen';
        $commands[] = '-y';
        $commands[] = $palette;

        $commands_2[] = '-i';
        $commands_2[] = $palette;
        $commands_2[] = '-lavfi';
        $commands_2[] = $filters . ' [x]; [x][1:v] paletteuse';
        $commands_2[] = '-y';

        $commands_2 = array_merge($commands_2, array($pathfile));

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to generate palette', $e->getCode(), $e);
        }

        try {
            $this->driver->command($commands_2);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save gif', $e->getCode(), $e);
        }

        return $this;
    }
}
