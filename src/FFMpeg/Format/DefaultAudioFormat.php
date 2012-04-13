<?php

namespace FFMpeg\Format;

abstract class DefaultAudioFormat implements AudioFormat
{

    protected $audioCodec;
    protected $audioSampleRate = 44100;
    protected $kiloBitrate     = 1000;

    public function getExtraParams()
    {
        return '';
    }

    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    public function setAudioCodec($audioCodec)
    {
        if ( ! in_array($audioCodec, $this->getAvailableAudioCodecs()))
        {
            throw new \InvalidArgumentException('Wrong audiocodec value');
        }

        $this->audioCodec = $audioCodec;
    }

    public function getAudioSampleRate()
    {
        return $this->audioSampleRate;
    }

    public function setAudioSampleRate($audioSampleRate)
    {
        if ($audioSampleRate < 1)
        {
            throw new \InvalidArgumentException('Wrong audio sample rate value');
        }

        $this->audioSampleRate = (int) $audioSampleRate;
    }

    public function getKiloBitrate()
    {
        return $this->kiloBitrate;
    }

    public function setKiloBitrate($kiloBitrate)
    {
        if ($kiloBitrate < 1)
        {
            throw new \InvalidArgumentException('Wrong kiloBitrate value');
        }

        $this->kiloBitrate = (int) $kiloBitrate;
    }

    abstract protected function getAvailableAudioCodecs();

}
