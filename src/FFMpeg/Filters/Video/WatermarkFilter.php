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

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\AdvancedMedia\ComplexCompatibleFilter;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\AdvancedMedia;
use FFMpeg\Media\Video;

class WatermarkFilter implements VideoFilterInterface, ComplexCompatibleFilter
{
    /** @var string */
    private $watermarkPath;
    /** @var array */
    private $coordinates;
    /** @var integer */
    private $priority;

    public function __construct($watermarkPath, array $coordinates = array(), $priority = 0)
    {
        if (!file_exists($watermarkPath)) {
            throw new InvalidArgumentException(sprintf('File %s does not exist', $watermarkPath));
        }

        $this->watermarkPath = $watermarkPath;
        $this->coordinates = $coordinates;
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
     * Get name of the filter.
     *
     * @return string
     */
    public function getName()
    {
        return 'watermark';
    }

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion()
    {
        return '0.8';
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        return $this->getCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(AdvancedMedia $media)
    {
        return $this->getCommands();
    }

    /**
     * @return array
     */
    protected function getCommands()
    {
        $position = isset($this->coordinates['position']) ? $this->coordinates['position'] : 'absolute';

        switch ($position) {
            case 'relative':
                if (isset($this->coordinates['top'])) {
                    $y = $this->coordinates['top'];
                } elseif (isset($this->coordinates['bottom'])) {
                    $y = 'main_h - ' . $this->coordinates['bottom'] . ' - overlay_h';
                } else {
                    $y = 0;
                }

                if (isset($this->coordinates['left'])) {
                    $x = $this->coordinates['left'];
                } elseif (isset($this->coordinates['right'])) {
                    $x = 'main_w - ' . $this->coordinates['right'] . ' - overlay_w';
                } else {
                    $x = 0;
                }

                break;
            default:
                $x = isset($this->coordinates['x']) ? $this->coordinates['x'] : 0;
                $y = isset($this->coordinates['y']) ? $this->coordinates['y'] : 0;
                break;
        }

        return array(
            '-vf',
            'movie=' . $this->watermarkPath . ' [watermark]; [in][watermark] overlay=' . $x . ':' . $y . ' [out]',
        );
    }
}
