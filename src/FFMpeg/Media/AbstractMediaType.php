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
use Spatie\TemporaryDirectory\TemporaryDirectory;

abstract class AbstractMediaType implements MediaTypeInterface
{
    /** @var string */
    protected $pathfile;
    /** @var FFMpegDriver */
    protected $driver;
    /** @var FFProbe */
    protected $ffprobe;
    /** @var FiltersCollection */
    protected $filters;

    public function __construct($pathfile, FFMpegDriver $driver, FFProbe $ffprobe)
    {
        $this->pathfile = $pathfile;
        $this->driver = $driver;
        $this->ffprobe = $ffprobe;
        $this->filters = new FiltersCollection();
    }

    /**
     * @return FFMpegDriver
     */
    public function getFFMpegDriver()
    {
        return $this->driver;
    }

    /**
     * @return MediaTypeInterface
     */
    public function setFFMpegDriver(FFMpegDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return FFProbe
     */
    public function getFFProbe()
    {
        return $this->ffprobe;
    }

    /**
     * @return MediaTypeInterface
     */
    public function setFFProbe(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathfile()
    {
        return $this->pathfile;
    }

    /**
     * @return MediaTypeInterface
     */
    public function setFiltersCollection(FiltersCollection $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return MediaTypeInterface
     */
    public function getFiltersCollection()
    {
        return $this->filters;
    }

    /**
     * Returns a new instance of TemporaryDirectory with the optionally configured directory.
     *
     * @return \Spatie\TemporaryDirectory\TemporaryDirectory
     */
    public function getTemporaryDirectory(): TemporaryDirectory
    {
        return new TemporaryDirectory(
            $this->driver->getConfiguration()->get('temporary_directory') ?: ''
        );
    }

    protected function cleanupTemporaryFile($filename)
    {
        if (file_exists($filename) && is_writable($filename)) {
            unlink($filename);
        }

        return $this;
    }
}
