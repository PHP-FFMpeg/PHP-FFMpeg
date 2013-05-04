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

use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Dimension;
use FFMpeg\Exception\InvalidArgumentException;

/**
 * The abstract default Video format
 *
 * @author Romain Neutron imprec@gmail.com
 */
abstract class DefaultVideo extends DefaultAudio implements Interactive, Resamplable, Resizable
{
    const RESIZEMODE_FIT = 'fit';
    const RESIZEMODE_INSET = 'inset';
    const RESIZEMODE_SCALE_WIDTH = 'width';
    const RESIZEMODE_SCALE_HEIGHT = 'height';

    protected $width;
    protected $height;
    protected $frameRate = 25;
    protected $resizeMode = self::RESIZEMODE_FIT;
    protected $videoCodec;
    protected $GOPsize = 25;
    protected $kiloBitrate = 1000;
    protected $modulus = 16;

    /**
     * Returns the width setting.
     * The return of this method should not depend on a media file size
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Returns the height setting
     * The return of this method should not depend on a media file size
     *
     * @return integer
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

        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * {@inheritdoc)
     */
    public function getComputedDimensions($originalWidth, $originalHeight)
    {
        $originalRatio = $originalWidth / $originalHeight;

        switch ($this->getResizeMode()) {
            case self::RESIZEMODE_SCALE_WIDTH:
                $height = $this->height;
                $width = round($originalRatio * $this->height);
                break;
            case self::RESIZEMODE_SCALE_HEIGHT:
                $width = $this->width;
                $height = round($this->width / $originalRatio);
                break;
            case self::RESIZEMODE_INSET:
                $targetRatio = $this->width / $this->height;

                if ($targetRatio > $originalRatio) {
                    $height = $this->height;
                    $width = round($originalRatio * $this->height);
                } else {
                    $width = $this->width;
                    $height = round($this->width / $originalRatio);
                }
                break;
            case self::RESIZEMODE_FIT:
            default:
                if (null !== $this->width && null !== $this->height) {
                    $width = $this->width;
                    $height = $this->height;
                } else {
                    $width = $originalWidth;
                    $height = $originalHeight;
                }
                break;
        }

        return new Dimension($width, $height);
    }

    /**
     * Set the resize mode
     *
     * @param string $mode The mode, one of the self::RESIZEMODE_* constants
     *
     * @throws InvalidArgumentException
     */
    public function setResizeMode($mode)
    {
        if ( ! in_array($mode, array(self::RESIZEMODE_FIT, self::RESIZEMODE_INSET, self::RESIZEMODE_SCALE_WIDTH, self::RESIZEMODE_SCALE_HEIGHT))) {
            throw new InvalidArgumentException(
                'Resize mode `%s` is not valid , avalaible values are %s',
                $mode,
                implode(', ', array(self::RESIZEMODE_FIT, self::RESIZEMODE_INSET, self::RESIZEMODE_SCALE_WIDTH, self::RESIZEMODE_SCALE_HEIGHT))
            );
        }

        $this->resizeMode = $mode;

        return $this;
    }

    /**
     * Get the current resize mode name
     *
     * @return string
     */
    public function getResizeMode()
    {
        return $this->resizeMode;
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
     * {@inheritDoc}
     */
    public function getPasses()
    {
        return 1;
    }

    /**
     * Used to determine what resolutions sizes are valid.
     *
     * @param int $value
     */
    public function setModulus($value)
    {
        if(!in_array($value, array(2, 4, 8, 16))){
            throw new InvalidArgumentException('Wrong modulus division value. Valid values are 2, 4, 8 or 16');
        }

        $this->modulus = $value;
    }

    /**
     * @return int
     */
    public function getModulus()
    {
        return $this->modulus;
    }
}
