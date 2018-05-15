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

    /**
     * @var Audio
     */
    protected $media;

    /**
     * Initializes the filters
     *
     * @param Audio $media The audio the filters will be applied to
     */
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
    public function resample(int $rate)
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }

    /**
     * Add metadata to an audio file. If no arguments are given then filter
     * will remove all metadata from the audio file
     *
     * @param string[][]|null $data If array must contain one of these key/value pairs:
     *                              * `title`: Title metadata
     *                              * `artist`: Artist metadata
     *                              * `composer`: Composer metadata
     *                              * `album`: Album metadata
     *                              * `track`: Track metadata
     *                              * `artwork`: Song artwork. String of (absolute) file path
     *                              * `year`: Year metadata
     *                              * `genre`: Genre metadata
     *                              * `description`: Description metadata
     */
    public function addMetadata(?array $data = null)
    {
        $this->media->addFilter(new AddMetadataFilter($data));

        return $this;
    }

    /**
     * Cuts the audio at `$start`, optionally define the end
     *
     * @param  TimeCode      $start    Where the clipping starts(seek to time)
     * @param  TimeCode|null $duration How long the clipped audio should be
     * @return AudioFilters
     */
    public function clip(TimeCode $start, ?TimeCode $duration = null)
    {
        $this->media->addFilter(new AudioClipFilter($start, $duration));

        return $this;
    }

    /**
     * Adds the `$artwork` to the audio and converts it to a video file.
     *
     * @param string $artwork  The artwork image link to add to the video
     * @param string $preset Certain encoding speed for selecting compression ratios.
     * @return AudioFilters
     */
    public function addVideoArtwork(string $artwork, string $preset) : AudioFilters
    {
        $this->media->addFilter(new ImageVideoFilter($artwork, $preset));

        return $this;
    }
}
