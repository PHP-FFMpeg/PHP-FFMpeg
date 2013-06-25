<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class ResizeFilter implements VideoFilterInterface
{
    const RESIZEMODE_FIT = 'fit';
    const RESIZEMODE_INSET = 'inset';
    const RESIZEMODE_SCALE_WIDTH = 'width';
    const RESIZEMODE_SCALE_HEIGHT = 'height';

    /** @var Dimension */
    private $dimension;
    /** @var string */
    private $mode;
    /** @var Boolean */
    private $forceStandards;

    public function __construct(Dimension $dimension, $mode = self::RESIZEMODE_FIT, $forceStandards = true)
    {
        $this->dimension = $dimension;
        $this->mode = $mode;
        $this->forceStandards = $forceStandards;
    }

    /**
     * @return Dimension
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return Boolean
     */
    public function areStandardsForced()
    {
        return $this->forceStandards;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $originalWidth = $originalHeight = null;

        foreach ($video->getStreams() as $stream) {
            if ($stream->isVideo()) {
                if ($stream->has('width')) {
                    $originalWidth = $stream->get('width');
                }
                if ($stream->has('height')) {
                    $originalHeight = $stream->get('height');
                }
            }
        }

        $commands = array();

        if ($originalHeight !== null && $originalWidth !== null) {
            $dimensions = $this->getComputedDimensions(new Dimension($originalWidth, $originalHeight), $format->getModulus());

            $commands[] = '-s';
            $commands[] = $dimensions->getWidth() . 'x' . $dimensions->getHeight();
        }

        return $commands;
    }

    private function getComputedDimensions(Dimension $dimension, $modulus)
    {
        $originalRatio = $dimension->getRatio($this->forceStandards);

        switch ($this->mode) {
            case self::RESIZEMODE_SCALE_WIDTH:
                $height = $this->dimension->getHeight();
                $width = $originalRatio->calculateWidth($height, $modulus);
                break;
            case self::RESIZEMODE_SCALE_HEIGHT:
                $width = $this->dimension->getWidth();
                $height = $originalRatio->calculateHeight($width, $modulus);
                break;
            case self::RESIZEMODE_INSET:
                $targetRatio = $this->dimension->getRatio($this->forceStandards);

                if ($targetRatio->getValue() > $originalRatio->getValue()) {
                    $height = $this->dimension->getHeight();
                    $width = $originalRatio->calculateWidth($height, $modulus);
                } else {
                    $width = $this->dimension->getWidth();
                    $height = $originalRatio->calculateHeight($width, $modulus);
                }
                break;
            case self::RESIZEMODE_FIT:
            default:
                $width = $this->dimension->getWidth();
                $height = $this->dimension->getHeight();
                break;
        }

        return new Dimension($width, $height);
    }
}
