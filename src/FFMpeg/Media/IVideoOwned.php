<?php

declare(strict_types=1);

namespace FFMpeg\Media;

/**
 * Represents media types that are originating from a video, allowing
 * the user to access its raw video object.
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2019
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  Media
 * @since       1.0
 */
interface IVideoOwned
{
    /**
     * Returns the video object belonging to this media type.
     *
     * @return Video
     */
    public function getVideo(): Video;
}
