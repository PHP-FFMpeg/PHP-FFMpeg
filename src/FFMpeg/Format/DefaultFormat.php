<?php

namespace FFMpeg\Format;

abstract class DefaultFormat implements Format
{

    protected $width;
    protected $height;
    protected $frameRate       = 25;
    protected $audioCodec;
    protected $audioSampleRate = 44100;
    protected $videoCodec;
    protected $kiloBitrate     = 1000;
    protected $GOPsize         = 25;

    public function __construct($width, $height)
    {
        $this->setDimensions($width, $height);
    }

    public function getExtraParams()
    {
        return '';
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setDimensions($width, $height)
    {
        if ($width < 1)
        {
            throw new \InvalidArgumentException('Wrong width value');
        }
        if ($height < 1)
        {
            throw new \InvalidArgumentException('Wrong height value');
        }

        $this->width = $this->getMultiple($width, 16);
        $this->height = $this->getMultiple($height, 16);

        return;
    }

    public function getFrameRate()
    {
        return $this->frameRate;
    }

    public function setFrameRate($frameRate)
    {
        if ($frameRate < 1)
        {
            throw new \InvalidArgumentException('Wrong framerate value');
        }

        $this->frameRate = (int) $frameRate;
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

    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    public function setVideoCodec($videoCodec)
    {
        if ( ! in_array($videoCodec, $this->getAvailableVideoCodecs()))
        {
            throw new \InvalidArgumentException('Wrong videocodec value');
        }

        $this->videoCodec = $videoCodec;
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

    public function getGOPsize()
    {
        return $this->GOPsize;
    }

    public function setGOPsize($GOPsize)
    {
        if ($GOPsize < 1)
        {
            throw new \InvalidArgumentException('Wrong GOP size value');
        }

        $this->GOPsize = (int) $GOPsize;
    }

    protected function getMultiple($value, $multiple)
    {
        $modulo = $value % $multiple;

        $ret = (int) $multiple;

        $halfDistance = $multiple / 2;
        if ($modulo <= $halfDistance)
            $bound        = 'bottom';
        else
            $bound        = 'top';

        switch ($bound)
        {
            default:
            case 'top':
                $ret = $value + $multiple - $modulo;
                break;
            case 'bottom':
                $ret = $value - $modulo;
                break;
        }

        if ($ret < $multiple)
        {
            $ret = (int) $multiple;
        }

        return (int) $ret;
    }

    abstract protected function getAvailableAudioCodecs();

    abstract protected function getAvailableVideoCodecs();

}