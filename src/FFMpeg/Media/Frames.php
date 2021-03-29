<?php

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Filters\Frame\FrameFilterInterface;
use FFMpeg\Filters\Frame\FrameFilters;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\TimeCode;

class Frames extends AbstractMediaType
{
    /** @var string */
    private $fps;
    /** @var string */
    private $scale;
    /** @var Video */
    private $video;

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, string $fps, ?string $scacle = null)
    {
        parent::__construct($video->getPathfile(), $driver, $ffprobe);
        $this->fps = $fps;
        $this->scacle = $scacle;
        $this->video = $video;
    }

    /**
     * {@inheritdoc}
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Returns the video related to the frame.
     *
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @return TimeCode
     */
    public function getFps()
    {
        return $this->fps;
    }

    /**
     * @return TimeCode
     */
    public function getScale()
    {
        return $this->scacle;
    }

    /**
     * Saves the frame in the given filename.
     *
     * Uses the `unaccurate method by default.`
     *
     * @param string  $pathfile
     * @param Boolean $accurate
     *
     * @return Frame
     *
     * @throws RuntimeException
     */
    public function save($pathfile, $accurate = false, $returnBase64 = false)
    {
        /**
         * might be optimized with http://ffmpeg.org/trac/ffmpeg/wiki/Seeking%20with%20FFmpeg
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        $outputFormat = $returnBase64 ? "image2pipe" : "image2";
        if (!$accurate) {
            $commands = array(
                '-y',
                '-i', $this->pathfile,
                '-f', $outputFormat
            );
        } else {
            $commands = array(
                '-y', '-i', $this->pathfile,
                '-f', $outputFormat
            );
        }

        $commands[] = '-vf';

        if ($this->getScale()) {
            $commands[] = 'scale=' . $this->getScale() . ',fps=' . $this->getFps();
        } else {
            $commands[] = 'fps=' . $this->getFps();
        }

        if($returnBase64) {
            array_push($commands, "-");
        }

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        if (!$returnBase64) {
            $commands = array_merge($commands, array($pathfile));
        }

        try {
            if(!$returnBase64) {
                $this->driver->command($commands);
                return $this;
            }
            else {
                return $this->driver->command($commands);
            }
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save frame', $e->getCode(), $e);
        }
    }
}
