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
use FFMpeg\Exception\InvalidArgumentException;

/**
 * The abstract default Video format
 *
 * @author Romain Neutron imprec@gmail.com
 */
abstract class DefaultVideo extends DefaultAudio implements InteractiveVideo, ResamplableVideo, ResizableVideo
{
    const RESIZEMODE_FIT = 'fit';
    const RESIZEMODE_INSET = 'inset';

    protected $width;
    protected $height;
    protected $frameRate = 25;
    protected $resizeMode = self::RESIZEMODE_FIT;
    protected $videoCodec;
    protected $GOPsize = 25;
    protected $kiloBitrate = 1000;

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

        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * {@inheritdoc)
     */
    public function getComputedDimensions($originalWidth, $originalHeight)
    {
        switch ($this->getResizeMode()) {
            case self::RESIZEMODE_INSET:
                $originalRatio = $originalWidth / $originalHeight;
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
                $width = $this->width;
                $height = $this->height;
                break;
        }

        return array($width, $height);
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
        if ( ! in_array($mode, array(self::RESIZEMODE_FIT, self::RESIZEMODE_INSET))) {
            throw new InvalidArgumentException(
                'Resize mode `%s` is not valid , avalaible values are %s',
                $mode,
                implode(', ', array(self::RESIZEMODE_FIT, self::RESIZEMODE_INSET))
            );
        }

        $this->resizeMode = $mode;

        return $this;
    }

    /**
     * {@inheritdoc}
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
}
