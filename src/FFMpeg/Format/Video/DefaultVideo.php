<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

use FFMpeg\FFProbe;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Format\ProgressListener\VideoProgressListener;

/**
 * The abstract default Video format
 */
abstract class DefaultVideo extends DefaultAudio implements VideoInterface
{
    protected $videoCodec;
    protected $kiloBitrate = 1000;
    protected $modulus = 16;

    /**
     * {@inheritdoc}
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * Set the video codec, Should be in the available ones, otherwise an
     * exception is thrown
     *
     * @param  string                    $videoCodec
     * @throws \InvalidArgumentException
     */
    public function setVideoCodec($videoCodec)
    {
        if ( ! in_array($videoCodec, $this->getAvailableVideoCodecs())) {
            throw new InvalidArgumentException(sprintf(
                    'Wrong videocodec value for %s, available formats are %s'
                    , $videoCodec, implode(', ', $this->getAvailableVideoCodecs())
            ));
        }

        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPasses()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getModulus()
    {
        return $this->modulus;
    }

    public function createProgressListener(FFProbe $ffprobe, $pathfile)
    {
        $format = $this;
        $listeners = array(new VideoProgressListener($ffprobe, $pathfile));

        foreach ($listeners as $listener) {
            $listener->on('progress', function () use ($format) {
               $format->emit('progress', func_get_args());
            });
        }

        return $listeners;
    }
}
