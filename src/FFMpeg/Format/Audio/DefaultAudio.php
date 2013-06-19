<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Audio;

use Evenement\EventEmitter;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\ProgressListener\AudioProgressListener;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;

abstract class DefaultAudio extends EventEmitter implements AudioInterface, ProgressableInterface
{
    protected $audioCodec;
    protected $kiloBitrate = 128;

    /**
     * {@inheritdoc}
     */
    public function getExtraParams()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    /**
     * Set the audio codec, Should be in the available ones, otherwise an
     * exception is thrown
     *
     * @param  string                    $audioCodec
     * @throws \InvalidArgumentException
     */
    public function setAudioCodec($audioCodec)
    {
        if ( ! in_array($audioCodec, $this->getAvailableAudioCodecs())) {
            throw new InvalidArgumentException(sprintf(
                    'Wrong audiocodec value for %s, available formats are %s'
                    , $audioCodec, implode(', ', $this->getAvailableAudioCodecs())
            ));
        }

        $this->audioCodec = $audioCodec;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKiloBitrate()
    {
        return $this->kiloBitrate;
    }

    /**
     * Set the kiloBitrate value
     *
     * @param  int integer               $kiloBitrate
     * @throws \InvalidArgumentException
     */
    public function setKiloBitrate($kiloBitrate)
    {
        if ($kiloBitrate < 1) {
            throw new InvalidArgumentException('Wrong kiloBitrate value');
        }

        $this->kiloBitrate = (int) $kiloBitrate;

        return $this;
    }

    public function createProgressListener(FFProbe $ffprobe, $pathfile)
    {
        $format = $this;
        $listener = new AudioProgressListener($ffprobe, $pathfile);
        $listener->on('progress', function () use ($format) {
           $format->emit('progress', func_get_args());
        });

        return array($listener);
    }
}
