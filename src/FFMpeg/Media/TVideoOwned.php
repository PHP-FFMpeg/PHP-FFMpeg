<?php

declare(strict_types=1);

namespace FFMpeg\Media;

/**
 * Generic implementation for the `IVideoOwned` interface.
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2019
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  Media
 * @since       1.0
 */
trait TVideoOwned
{
    /**
     * @inheritDoc
     */
    public function getVideo(): Video
    {
        return $this->video;
    }
}
