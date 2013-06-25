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

class Stream extends AbstractData
{
    /**
     * Returns true if the stream is an audio stream.
     *
     * @return Boolean
     */
    public function isAudio()
    {
        return $this->has('codec_type') ? 'audio' === $this->get('codec_type') : false;
    }

    /**
     * Returns true if the stream is a video stream.
     *
     * @return Boolean
     */
    public function isVideo()
    {
        return $this->has('codec_type') ? 'video' === $this->get('codec_type') : false;
    }
}
