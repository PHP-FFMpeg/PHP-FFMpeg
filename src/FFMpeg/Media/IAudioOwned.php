<?php

declare(strict_types=1);

namespace FFMpeg\Media;

/**
 * Represents media types that are originating from a audio, allowing
 * the user to access its raw audio object.
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2019
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  Media
 * @since       1.0
 */
interface IAudioOwned
{
    /**
     * Returns the audio object belonging to this media type.
     *
     * @return Audio
     */
    public function getAudio(): Audio;
}
