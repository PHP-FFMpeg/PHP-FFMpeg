<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe\DataMapping;

class Frame extends AbstractData
{
    /**
     * Returns true if the frame is an audio frame.
     *
     * @return Boolean
     */
    public function isAudio()
    {
        return $this->get('media_type') === 'audio';
    }

    /**
     * Returns true if the frame is a video frame.
     *
     * @return Boolean
     */
    public function isVideo()
    {
        return $this->get('media_type') === 'video';
    }
}
