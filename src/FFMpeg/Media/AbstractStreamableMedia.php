<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

abstract class AbstractStreamableMedia extends AbstractMediaType
{
    public function getStreams()
    {
        return $this->ffprobe->streams($this->pathfile);
    }

    public function getFormat()
    {
        return $this->ffprobe->format($this->pathfile);
    }
}
