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
use FFMpeg\Filters\Waveform\WaveformFilterInterface;
use FFMpeg\Filters\Waveform\WaveformFilters;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;

class Waveform extends AbstractMediaType
{
    /** @var Video */
    private $audio;
    private $width;
    private $height;

    public function __construct(Audio $audio, FFMpegDriver $driver, FFProbe $ffprobe, $width, $height)
    {
        parent::__construct($audio->getPathfile(), $driver, $ffprobe);
        $this->audio = $audio;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Returns the audio related to the waveform.
     *
     * @return Audio
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * {@inheritdoc}
     *
     * @return WaveformFilters
     */
    public function filters()
    {
        return new WaveformFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Waveform
     */
    public function addFilter(WaveformFilterInterface $filter)
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * Saves the waveform in the given filename.
     *
     * @param string  $pathfile
     *
     * @return Waveform
     *
     * @throws RuntimeException
     */
    public function save($pathfile)
    {
        /**
         * might be optimized with http://ffmpeg.org/trac/ffmpeg/wiki/Seeking%20with%20FFmpeg
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        $commands = array(
            '-i', $this->pathfile, '-filter_complex',
            'showwavespic=s='.$this->width.'x'.$this->height,
            '-frames:v', '1'
        );

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        $commands = array_merge($commands, array($pathfile));

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save waveform', $e->getCode(), $e);
        }

        return $this;
    }
}
