<?php

declare(strict_types=1);

namespace FFMpeg\Filters\Audio;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

/**
 * Converts the audio to video and `artwork` as video content.
 *
 * @author     pralhad.shrestha05@gmail.com
 * @copyright  PHP-FFMpeg and Contributors
 * @license    MIT License
 * @package    FFMpeg\Filters
 * @subpackage Audio
 */
class ImageVideoFilter implements AudioFilterInterface
{
    use TPriorityFilter;

    protected const FFMPEG_PRESETS = [
        'ultrafast', 'superfast', 'veryfast', 'faster', 'fast', 'medium', 'slow', 'slower', 'veryslow',
    ];

    /**
     * @var string
     */
    private $artwork;

    /**
     * @var string
     */
    private $preset;

    /**
     * @var int
     */
    private $priority;

    public function __construct(string $artwork, string $preset, int $priority = 10)
    {
        $this->artwork = $artwork;
        $this->preset = $preset;
        $this->priority = $priority;
    }

    /**
     * @inheritDoc
     */
    public function apply(Audio $audio, AudioInterface $format): array
    {
        if (!in_array($this->preset, self::FFMPEG_PRESETS)) {
            throw new InvalidArgumentException('Undefined preset. Please pass a preset type to the method.');
        }

        return ['-loop', '1', '-i', $this->artwork, '-preset', $this->preset, '-shortest'];
    }
}
