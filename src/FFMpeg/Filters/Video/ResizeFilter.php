<?php

declare(strict_types=1);

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
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class ResizeFilter implements VideoFilterInterface
{
    use TPriorityFilter;

    /** fits to the dimensions, might introduce anamorphosis */
    const RESIZEMODE_FIT = 'fit';
    /** resizes the video inside the given dimension, no anamorphosis */
    const RESIZEMODE_INSET = 'inset';
    /** resizes the video to fit the dimension width, no anamorphosis */
    const RESIZEMODE_SCALE_WIDTH = 'width';
    /** resizes the video to fit the dimension height, no anamorphosis */
    const RESIZEMODE_SCALE_HEIGHT = 'height';

    /** @var Dimension */
    private $dimension;
    /** @var string */
    private $mode;
    /** @var bool */
    private $forceStandards;
    /** @var int */
    private $priority;

    public function __construct(Dimension $dimension, string $mode = self::RESIZEMODE_FIT, bool $forceStandards = true, int $priority = 0)
    {
        $this->dimension = $dimension;
        $this->mode = $mode;
        $this->forceStandards = $forceStandards;
        $this->priority = $priority;
    }

    /**
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return bool
     */
    public function areStandardsForced(): bool
    {
        return $this->forceStandards;
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $dimensions = null;
        $commands = [];

        foreach ($video->getStreams()->videos() as $stream) {
            try {
                $dimensions = $stream->getDimensions();
                break;
            } catch (RuntimeException $e) {
                // ignore
            }
        }

        if (null !== $dimensions) {
            $dimensions = $this->getComputedDimensions($dimensions, $format->getModulus());

            // Using Filter to have ordering
            $commands[] = '-vf';
            $commands[] = '[in]scale=' . $dimensions->getWidth() . ':' . $dimensions->getHeight() . ' [out]';
        }

        return $commands;
    }

    private function getComputedDimensions(Dimension $dimension, int $modulus): Dimension
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

        // float dimensions like 1.223232323 don't make that much sense …
        // the (int) cast rounds then down to the next integer ((int) M_PI -> 3)
        return new Dimension((int) $width, (int) $height);
    }
}
