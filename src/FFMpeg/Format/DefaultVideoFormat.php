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

use FFMpeg\Exception\InvalidArgumentException;

/**
 * The abstract default Video format
 *
 * @author Romain Neutron imprec@gmail.com
 */
abstract class DefaultVideoFormat extends DefaultAudioFormat implements VideoFormat
{
    protected $width;
    protected $height;
    protected $frameRate = 25;
    protected $videoCodec;
    protected $GOPsize = 25;
    protected $kiloBitrate = 1000;

    /**
     * Constructor
     *
     * @param integer $width
     * @param integer $height The height of the video format
     */
    public function __construct($width, $height)
    {
        $this->setDimensions($width, $height);
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the dimensions
     *
     * @param  integer                   $width  The heigth
     * @param  integer                   $height The width
     * @throws \InvalidArgumentException
     */
    public function setDimensions($width, $height)
    {
        if ($width < 1) {
            throw new InvalidArgumentException('Wrong width value');
        }
        if ($height < 1) {
            throw new InvalidArgumentException('Wrong height value');
        }

        $this->width = $this->getMultiple($width, 16);
        $this->height = $this->getMultiple($height, 16);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFrameRate()
    {
        return $this->frameRate;
    }

    /**
     * Set the framerate
     *
     * @param integer $frameRate
     *
     * @throws \InvalidArgumentException
     */
    public function setFrameRate($frameRate)
    {
        if ($frameRate < 1) {
            throw new InvalidArgumentException('Wrong framerate value');
        }

        $this->frameRate = (int) $frameRate;

        return $this;
    }

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
     * {@inheritdoc}
     */
    public function getGOPsize()
    {
        return $this->GOPsize;
    }

    /**
     * Set the GOP size
     *
     * @param integer $GOPsize
     *
     * @throws \InvalidArgumentException
     */
    public function setGOPsize($GOPsize)
    {
        if ($GOPsize < 1) {
            throw new InvalidArgumentException('Wrong GOP size value');
        }

        $this->GOPsize = (int) $GOPsize;

        return $this;
    }

    /**
     * Returns the nearest multiple for a value
     *
     * @param  integer $value
     * @param  integer $multiple
     * @return integer
     */
    protected function getMultiple($value, $multiple)
    {
        $modulo = $value % $multiple;

        $ret = (int) $multiple;

        $halfDistance = $multiple / 2;
        if ($modulo <= $halfDistance)
            $bound = 'bottom';
        else
            $bound = 'top';

        switch ($bound) {
            default:
            case 'top':
                $ret = $value + $multiple - $modulo;
                break;
            case 'bottom':
                $ret = $value - $modulo;
                break;
        }

        if ($ret < $multiple) {
            $ret = (int) $multiple;
        }

        return (int) $ret;
    }
}
