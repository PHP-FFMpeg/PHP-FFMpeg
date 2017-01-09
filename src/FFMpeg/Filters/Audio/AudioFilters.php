<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\Audio\AddMetadataFilter;
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
     * @param Integer $rate
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
     * will remove all metadata from the audio file
     * @param Array|Null $data  If array must contain one of these key/value pairs:
     *    - "title": Title metadata
     *    - "artist": Artist metadata
     *    - "composer": Composer metadata
     *    - "album": Album metadata
     *    - "track": Track metadata
     *    - "artwork": Song artwork. String of file path
     *    - "year": Year metadata
     *    - "genre": Genre metadata
     *    - "description": Description metadata
     */
    public function addMetadata($data = null)
    {
        $this->media->addFilter(new AddMetadataFilter($data));

        return $this;
    }
}
