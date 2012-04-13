<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format;

/**
 * The abstract default Video format
 *
 * @author Romain Neutron imprec@gmail.com
 */
abstract class DefaultVideoFormat extends DefaultAudioFormat implements VideoFormat
{

    protected $width;
    protected $height;
    protected $frameRate   = 25;
    protected $videoCodec;
    protected $GOPsize     = 25;
    protected $kiloBitrate = 1000;

    public function __construct($width, $height)
    {
        $this->setDimensions($width, $height);
    }

    /**
     * Returns the width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Returns the height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the dimensions
     *
     * @param int $width    The heigth
     * @param int $height   The width
     * @throws \InvalidArgumentException
     */
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
    }

    /**
     * Returns the framerate
     *
     * @return int
     */
    public function getFrameRate()
    {
        return $this->frameRate;
    }

    /**
     * Set the framerate
     *
     * @param int $frameRate
     * @throws \InvalidArgumentException
     */
    public function setFrameRate($frameRate)
    {
        if ($frameRate < 1)
        {
            throw new \InvalidArgumentException('Wrong framerate value');
        }

        $this->frameRate = (int) $frameRate;
    }

    /**
     * Returns the video codec
     *
     * @return string
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * Set the video codec, Should be in the available ones, otherwise an
     * exception is thrown
     *
     * @param string $videoCodec
     * @throws \InvalidArgumentException
     */
    public function setVideoCodec($videoCodec)
    {
        if ( ! in_array($videoCodec, $this->getAvailableVideoCodecs()))
        {
            throw new \InvalidArgumentException('Wrong videocodec value');
        }

        $this->videoCodec = $videoCodec;
    }

    /**
     * Returns the GOP size
     *
     * @return int
     */
    public function getGOPsize()
    {
        return $this->GOPsize;
    }

    /**
     * Set the GOP size
     *
     * @param int $GOPsize
     * @throws \InvalidArgumentException
     */
    public function setGOPsize($GOPsize)
    {
        if ($GOPsize < 1)
        {
            throw new \InvalidArgumentException('Wrong GOP size value');
        }

        $this->GOPsize = (int) $GOPsize;
    }

    /**
     * Returns the nearest multiple for a value
     *
     * @param int $value
     * @param int $multiple
     * @return int
     */
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

    /**
     * Returns the list of available video codecs for this format
     *
     * @return array
     */
    abstract public function getAvailableVideoCodecs();

}
