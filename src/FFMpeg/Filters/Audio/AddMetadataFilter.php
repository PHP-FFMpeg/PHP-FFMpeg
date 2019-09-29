<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\Audio\AudioFilterInterface;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

class AddMetadataFilter implements AudioFilterInterface
{
    use TPriorityFilter;

    /** @var string[][]|string[]|null */
    private $metaArr;
    /** @var int */
    private $priority;

    /**
     * It is recommended to have a higher priority here,
     * when there might be a second input stream registered with the audio.
     */
    public function __construct(?array $metaArr = null, int $priority = 9)
    {
        $this->metaArr = $metaArr;
        $this->priority = $priority;
    }

    public function apply(Audio $audio, AudioInterface $format): array
    {
        $meta = $this->metaArr;

        if (null === $meta) {
            return ['-map_metadata', '-1', '-vn'];
        }

        $metadata = [];

        if (isset($meta['artwork']) || \array_key_exists("artwork", $meta)) {
            $metadata[] = "-i";
            $metadata[] = $meta['artwork'];
            $metadata[] = "-map";
            $metadata[] = "0";
            $metadata[] = "-map";
            $metadata[] = "1";
            unset($meta['artwork']);
        }

        foreach ($meta as $k => $v) {
            $metadata[] = "-metadata";
            $metadata[] = "{$k}={$v}";
        }

        return $metadata;
    }
}
