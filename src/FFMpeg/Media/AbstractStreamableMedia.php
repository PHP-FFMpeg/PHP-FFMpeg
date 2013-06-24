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

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

abstract class AbstractStreamableMedia extends AbstractMediaType
{
    /**
     * @return StreamCollection
     */
    public function getStreams()
    {
        return $this->ffprobe->streams($this->pathfile);
    }

    /**
     * @return Stream
     */
    public function getFormat()
    {
        return $this->ffprobe->format($this->pathfile);
    }
}
