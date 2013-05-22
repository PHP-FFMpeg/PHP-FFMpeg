<?php

namespace FFMpeg\Media;

use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\FFProbe;
use FFMpeg\Filters\FiltersCollection;
use FFMpeg\Media\MediaTypeInterface;

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
        $this->ensureFileIsPresent($pathfile);

        $this->pathfile = $pathfile;
        $this->driver = $driver;
        $this->ffprobe = $ffprobe;
        $this->filters = new FiltersCollection();
    }

    /**
     * @param FiltersCollection $filters
     *
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

    protected function ensureFileIsPresent($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not present or not readable', $filename
            ));
        }
    }

    protected function cleanupTemporaryFile($filename)
    {
        if (file_exists($filename) && is_writable($filename)) {
            unlink($filename);
        }

        return $this;
    }
}
