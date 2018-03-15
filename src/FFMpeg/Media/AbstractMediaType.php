<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Filters\FiltersCollection;

abstract class AbstractMediaType implements MediaTypeInterface
{

    /**
     * @var string
     */
    protected $pathfile;

    /**
     * @var FFMpegDriver
     */
    protected $driver;

    /**
     * @var FFProbe
     */
    protected $ffprobe;

    /**
     * @var FiltersCollection
     */
    protected $filters;

    public function __construct(string $pathfile, FFMpegDriver $driver, FFProbe $ffprobe)
    {
        $this->pathfile = $pathfile;
        $this->driver = $driver;
        $this->ffprobe = $ffprobe;
        $this->filters = new FiltersCollection;
    }

    /**
     * @return FFMpegDriver
     */
    public function getFFMpegDriver(): FFMpegDriver
    {
        return $this->driver;
    }

    /**
     * @param FFMpegDriver $driver
     *
     * @return MediaTypeInterface
     */
    public function setFFMpegDriver(FFMpegDriver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return FFProbe
     */
    public function getFFProbe(): FFProbe
    {
        return $this->ffprobe;
    }

    /**
     * @param FFProbe $ffprobe
     *
     * @return MediaTypeInterface
     */
    public function setFFProbe(FFProbe $ffprobe): MediaTypeInterface
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathfile(): string
    {
        return $this->pathfile;
    }

    /**
     * @param FiltersCollection $filters
     *
     * @return MediaTypeInterface
     */
    public function setFiltersCollection(FiltersCollection $filters): MediaTypeInterface
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return MediaTypeInterface
     */
    public function getFiltersCollection(): MediaTypeInterface
    {
        return $this->filters;
    }

    protected function cleanupTemporaryFile(string $filename): self
    {
        if (file_exists($filename) && is_writable($filename)) {
            @unlink($filename);
        }

        return $this;
    }
}
