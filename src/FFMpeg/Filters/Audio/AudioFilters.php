<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Audio;

class AudioFilters
{
    protected $media;

    public function __construct(Audio $media)
    {
        $this->media = $media;
    }

    /**
     * Resamples the audio file.
     *
     * @param int $rate
     *
     * @return AudioFilters
     */
    public function resample($rate)
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }

    /**
     * Add metadata to an audio file. If no arguments are given then filter
     * will remove all metadata from the audio file.
     *
     * @param array|null $data If array must contain one of these key/value pairs:
     *                         - "title": Title metadata
     *                         - "artist": Artist metadata
     *                         - "composer": Composer metadata
     *                         - "album": Album metadata
     *                         - "track": Track metadata
     *                         - "artwork": Song artwork. String of file path
     *                         - "year": Year metadata
     *                         - "genre": Genre metadata
     *                         - "description": Description metadata
     */
    public function addMetadata($data = null)
    {
        $this->media->addFilter(new AddMetadataFilter($data));

        return $this;
    }

    /**
     * Cuts the audio at `$start`, optionally define the end.
     *
     * @param TimeCode $start    Where the clipping starts(seek to time)
     * @param TimeCode $duration How long the clipped audio should be
     *
     * @return AudioFilters
     */
    public function clip($start, $duration = null)
    {
        $this->media->addFilter(new AudioClipFilter($start, $duration));

        return $this;
    }

    /**
     * Applies a custom filter.
     *
     * @param string $parameters
     *
     * @return AudioFilters
     */
    public function custom($parameters)
    {
        $this->media->addFilter(new CustomFilter($parameters));

        return $this;
    }
}
