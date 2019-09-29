<?php

declare(strict_types=1);

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;
use FFMpeg\Coordinate\TimeCode;

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
    public function resample(int $rate): AudioFilters
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }

    /**
     * Add metadata to an audio file. If no arguments are given then the filter
     * will remove all metadata from the audio file.
     * @param string[][]|null $data  If array must contain one of these key/value pairs:
     *    - "title": Title metadata
     *    - "artist": Artist metadata
     *    - "composer": Composer metadata
     *    - "album": Album metadata
     *    - "track": Track metadata
     *    - "artwork": Song artwork. String of file path
     *    - "year": Year metadata
     *    - "genre": Genre metadata
     *    - "description": Description metadata
     *
     * @return AudioFilters
     */
    public function addMetadata(?array $data = null): AudioFilters
    {
        $this->media->addFilter(new AddMetadataFilter($data));

        return $this;
    }

    /**
     * Cuts the audio at `$start`, optionally define the end
     *
     * @param   TimeCode        $start      Where the clipping starts(seek to time)
     * @param   TimeCode|null   $duration   How long the clipped audio should be
     *
     * @return AudioFilters
     */
    public function clip(TimeCode $start, ?TimeCode $duration = null)
    {
        $this->media->addFilter(new AudioClipFilter($start, $duration));

        return $this;
    }

    /**
     * Applies a custom filter
     *
     * @param string    $parameters
     *
     * @return AudioFilters
     */
    public function custom($parameters)
    {
        $this->media->addFilter(new CustomFilter($parameters));

        return $this;
    }

    /**
     * Adds the `$artwork` to the audio and converts it to a video file.
     *
     * @param string $artwork  The artwork image link to add to the video
     * @param string $preset Certain encoding speed for selecting compression ratios.
     *
     * @return AudioFilters
     */
    public function addVideoArtwork(string $artwork, string $preset): AudioFilters
    {
        $this->media->addFilter(new ImageVideoFilter($artwork, $preset));

        return $this;
    }
}
