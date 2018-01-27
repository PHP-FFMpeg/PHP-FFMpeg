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

use FFMpeg\FFProbe\DataMapping\Format;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

abstract class AbstractStreamableMedia extends AbstractMediaType
{

    /**
     * @var StreamCollection
     */
    private $streams;

    /**
     * @return StreamCollection
     */
    public function getStreams(): StreamCollection
    {
        if ($this->streams === null) {
            $this->streams = $this->ffprobe->streams($this->pathfile);
        }

        return $this->streams;
    }

    /**
     * @return Format
     */
    public function getFormat(): Format
    {
        return $this->ffprobe->format($this->pathfile);
    }
}
