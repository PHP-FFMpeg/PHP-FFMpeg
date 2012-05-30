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

use FFMpeg\Exception\InvalidArgumentException;

/**
 * The abstract default Audio format
 *
 * @author Romain Neutron imprec@gmail.com
 */
abstract class DefaultAudio implements ResamplableAudio, InteractiveAudio
{
    protected $audioCodec;
    protected $audioSampleRate = 44100;
    protected $kiloBitrate = 128;

    /**
     * Returns extra parameters for the encoding
     *
     * @return string
     */
    public function getExtraParams()
    {
        return '';
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
    public function getAudioSampleRate()
    {
        return $this->audioSampleRate;
    }

    /**
     * Set the audio sample rate
     *
     * @param  integer                   $audioSampleRate
     * @throws \InvalidArgumentException
     */
    public function setAudioSampleRate($audioSampleRate)
    {
        if ($audioSampleRate < 1) {
            throw new InvalidArgumentException('Wrong audio sample rate value');
        }

        $this->audioSampleRate = (int) $audioSampleRate;

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
}
