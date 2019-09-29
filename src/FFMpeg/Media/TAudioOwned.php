<?php

declare(strict_types=1);

namespace FFMpeg\Media;

/**
 * Generic implementation for the `IAudioOwned` interface.
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2019
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  Media
 * @since       1.0
 */
trait TAudioOwned
{
    /**
     * @inheritDoc
     */
    public function getAudio(): Audio
    {
        return $this->audio;
    }
}
