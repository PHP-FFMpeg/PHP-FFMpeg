<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Filters\Audio\AudioFilterInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

/**
 * Useful for adding metadata to an audio stream(for example, add cover image)
 *
 * @author     jens1o
 * @copyright  Jens Hausdorf 2018
 * @license    MIT License
 * @package    FFMpeg\Filters
 * @subpackage Audio
 */
class AddMetadataFilter implements AudioFilterInterface
{

    use TPriorityFilter;

    /**
     * @var string[]
     */
    private $metaArr;
    /**
     * @var int
     */
    private $priority;


    public function __construct(array $metaArr = null, int $priority = 9)
    {
        $this->metaArr = $metaArr;
        $this->setPriority($priority);
    }

    /**
     * @inheritDoc
     */
    public function apply(Audio $audio, AudioInterface $format): array
    {
        $meta = $this->metaArr;

        if ($meta === null) {
            return ['-map_metadata', '-1', '-vn'];
        }

        $metadata = [];

        // handle artwork image
        if (isset($meta["artwork"])) {
            $metadata[] = '-i';
            $metadata[] = $meta['artwork'];
            $metadata[] = '-map';
            $metadata[] = '0';
            $metadata[] = '-map';
            $metadata[] = '1';
            unset($meta['artwork']);
        }

        foreach ($meta as $k => $v) {
            $metadata[] = '-metadata';
            $metadata[] = "{$k}={$v}";
        }

        return $metadata;
    }
}
