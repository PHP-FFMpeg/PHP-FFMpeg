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

/**
 * Class WatermarkFilter
 * @package FFMpeg\Filters\Video
 */
class WatermarkFilter implements VideoFilterInterface, ComplexCompatibleFilter
{
    const FILTER_NAME            = 'watermark';
    const MINIMAL_FFMPEG_VERSION = '0.8';

    const POSITION_PRESET   = 'preset';
    const POSITION_ABSOLUTE = 'absolute';
    const POSITION_RELATIVE = 'relative';

    const PRESET_PLACE_TOP_LEFT      = 'top-left';
    const PRESET_PLACE_TOP_CENTER    = 'top-center';
    const PRESET_PLACE_TOP_RIGHT     = 'top-right';
    const PRESET_PLACE_BOTTOM_LEFT   = 'bottom-left';
    const PRESET_PLACE_BOTTOM_CENTER = 'bottom-center';
    const PRESET_PLACE_BOTTOM_RIGHT  = 'bottom-right';
    const PRESET_PLACE_CENTER_LEFT   = 'center-left';
    const PRESET_PLACE_CENTER_CENTER = 'center-center';
    const PRESET_PLACE_CENTER_RIGHT  = 'center-right';

    /** @var string */
    private $watermarkPath;
    /** @var array */
    private $coordinates;
    /** @var integer */
    private $priority;

    /**
     * WatermarkFilter constructor.
     * @param       $watermarkPath
     * @param array $coordinates
     * @param int   $priority
     */
    public function __construct($watermarkPath, array $coordinates = array(), $priority = 0)
    {
        if (!file_exists($watermarkPath)) {
            throw new InvalidArgumentException(sprintf('File %s does not exist', $watermarkPath));
        }

        $this->watermarkPath = $watermarkPath;
        $this->coordinates   = $coordinates;
        $this->priority      = $priority;
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
        return self::FILTER_NAME;
    }

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion()
    {
        return self::MINIMAL_FFMPEG_VERSION;
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
        $position = isset($this->coordinates['position']) ? $this->coordinates['position'] : self::POSITION_ABSOLUTE;

        if ($position === self::POSITION_PRESET) {
            return $this->getCommandsForPreset();
        }

        if ($position === self::POSITION_RELATIVE) {
            if (isset($this->coordinates['top'])) {
                $y = $this->coordinates['top'];
            } elseif (isset($this->coordinates['bottom'])) {
                $y = "main_h - {$this->coordinates['bottom']} - overlay_h";
            } else {
                $y = 0;
            }

            if (isset($this->coordinates['left'])) {
                $x = $this->coordinates['left'];
            } elseif (isset($this->coordinates['right'])) {
                $x = "main_w - {$this->coordinates['right']} - overlay_w";
            } else {
                $x = 0;
            }
        } else { //Default self::POSITION_ABSOLUTE
            $x = isset($this->coordinates['x']) ? $this->coordinates['x'] : 0;
            $y = isset($this->coordinates['y']) ? $this->coordinates['y'] : 0;
        }

        return array(
            '-vf',
            "movie={$this->watermarkPath} [watermark]; [in][watermark] overlay={$x}:{$y} [out]",
        );
    }

    /**
     * @return array
     */
    protected function getCommandsForPreset()
    {
        $place = empty($this->coordinates['place']) ? self::PRESET_PLACE_TOP_LEFT : $this->coordinates['place'];
        $x     = empty($this->coordinates['x']) ? 0 : $this->coordinates['x'];
        $y     = empty($this->coordinates['y']) ? 0 : $this->coordinates['y'];

        switch ($place) {
            case self::PRESET_PLACE_CENTER_LEFT:
                $overlay = "{$x}:(H-h)/2+{$y}";
            break;
            case self::PRESET_PLACE_CENTER_CENTER:
                $overlay = "(W-w)/2+{$x}:(H-h)/2+{$y}";
            break;
            case self::PRESET_PLACE_CENTER_RIGHT:
                $overlay = "W-w+{$x}:(H-h)/2+{$y}";
            break;
            case self::PRESET_PLACE_BOTTOM_RIGHT:
                $overlay = "W-w+{$x}:H-h+{$y}";
            break;
            case self::PRESET_PLACE_BOTTOM_CENTER:
                $overlay = "(W-w)/2+{$x}:H-h+{$y}";
            break;
            case self::PRESET_PLACE_BOTTOM_LEFT:
                $overlay = "{$x}:H-h+{$y}";
            break;
            case self::PRESET_PLACE_TOP_RIGHT:
                $overlay = "W-w+{$x}:{$y}";
            break;
            case self::PRESET_PLACE_TOP_CENTER:
                $overlay = "(W-w)/2+{$x}:{$y}";
            break;
            case self::PRESET_PLACE_TOP_LEFT:
            default:
                $overlay = "{$x}:{$y}";
            break;
        }

        return array(
            '-vf',
            "movie={$this->watermarkPath} [watermark]; [in][watermark] overlay={$overlay} [out]",
        );
    }
}
