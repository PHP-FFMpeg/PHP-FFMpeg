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
use FFMpeg\Options\OptionsCollection;

abstract class AbstractMediaType implements MediaTypeInterface
{
    /** @var string */
    protected $pathfile;
    /** @var FFMpegDriver */
    protected $driver;
    /** @var FFProbe */
    protected $ffprobe;
    /** @var OptionsCollection */
    protected $options;

    public function __construct($pathfile, FFMpegDriver $driver, FFProbe $ffprobe)
    {
        $this->pathfile = $pathfile;
        $this->driver = $driver;
        $this->ffprobe = $ffprobe;
        $this->options = new OptionsCollection();
    }

    /**
     * @return FFMpegDriver
     */
    public function getFFMpegDriver()
    {
        return $this->driver;
    }

    /**
     * @param FFMpegDriver $driver
     *
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
     * @param FFProbe $ffprobe
     *
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
     * @param OptionsCollection $options
     *
     * @return MediaTypeInterface
     */
    public function setOptionsCollection(OptionsCollection $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return OptionsCollection
     */
    public function getOptionsCollection()
    {
        return $this->options;
    }

    protected function cleanupTemporaryFile($filename)
    {
        if (file_exists($filename) && is_writable($filename)) {
            unlink($filename);
        }

        return $this;
    }
}
