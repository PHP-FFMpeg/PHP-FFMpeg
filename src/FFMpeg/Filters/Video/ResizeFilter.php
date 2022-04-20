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
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class ResizeFilter implements VideoFilterInterface
{
    /** fits to the dimensions, might introduce anamorphosis */
    public const RESIZEMODE_FIT = 'fit';
    /** resizes the video inside the given dimension, no anamorphosis */
    public const RESIZEMODE_INSET = 'inset';
    /** resizes the video to fit the dimension width, no anamorphosis */
    public const RESIZEMODE_SCALE_WIDTH = 'width';
    /** resizes the video to fit the dimension height, no anamorphosis */
    public const RESIZEMODE_SCALE_HEIGHT = 'height';

    /** @var Dimension */
    private $dimension;
    /** @var string */
    private $mode;
    /** @var bool */
    private $forceStandards;
    /** @var int */
    private $priority;

    public function __construct(Dimension $dimension, $mode = self::RESIZEMODE_FIT, $forceStandards = true, $priority = 0)
    {
        $this->dimension = $dimension;
        $this->mode = $mode;
        $this->forceStandards = $forceStandards;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
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
     * @return bool
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
        $dimensions = null;
        $commands = [];

        foreach ($video->getStreams() as $stream) {
            if ($stream->isVideo()) {
                try {
                    $dimensions = $stream->getDimensions();
                    break;
                } catch (RuntimeException $e) {
                }
            }
        }

        if (null !== $dimensions) {
            $dimensions = $this->getComputedDimensions($dimensions, $format->getModulus());

            // Using Filter to have ordering
            $commands[] = '-vf';
            $commands[] = '[in]scale='.$dimensions->getWidth().':'.$dimensions->getHeight().' [out]';
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
