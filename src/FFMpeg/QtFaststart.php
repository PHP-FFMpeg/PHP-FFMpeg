<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Akuma <meru.no.akuma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use Alchemy\BinaryDriver\ConfigurationInterface;
use FFMpeg\Driver\QtFaststartDriver;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Video;
use Psr\Log\LoggerInterface;

class QtFaststart
{
    /** @var QtFaststartDriver */
    private $driver;

    public function __construct(QtFaststartDriver $qtfaststart)
    {
        $this->driver = $qtfaststart;
    }

    /**
     * Sets the qtfaststart driver.
     *
     * @return FFMpeg
     */
    public function setQtFaststartDriver(QtFaststartDriver $qtfaststart)
    {
        $this->driver = $qtfaststart;

        return $this;
    }

    /**
     * Gets the qtfaststart driver.
     *
     * @return QtFaststartDriver
     */
    public function getQtFaststartDriver()
    {
        return $this->driver;
    }

    /**
     * Process a file with qt-faststart
     *
     * @param string $pathfile A pathfile
     *
     * @return QtFaststart
     *
     * @throws InvalidArgumentException
     */
    public function process($pathfile)
    {
        if (!file_exists($pathfile)) {
            throw new InvalidArgumentException(sprintf('File %s does not exists', $pathfile));
        }

        $tempfile = $pathfile.'.tmp';
        $tempfile2 = $pathfile.'.tmp2';

        if (!copy($pathfile, $tempfile)) {
            throw new InvalidArgumentException(sprintf('File %s cannot be copy to temp file', $pathfile));
        }

        $commands = array();
        $commands[] = $tempfile;
        $commands[] = $tempfile2;

        $failure = null;

        try {
            $this->driver->command($commands, false);
            if (!copy($tempfile2, $pathfile)) {
                throw new ExecutionFailureException('Can not copy temp to result file');
            }
        } catch (ExecutionFailureException $e) {
            $failure = $e;
            break;
        }

        @unlink($tempfile);
        @unlink($tempfile2);

        if (null !== $failure) {
            throw new RuntimeException('QtFaststart failed', $failure->getCode(), $failure);
        }

        return $this;
    }

    /**
     * Creates a new QtFaststart instance.
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface              $logger
     *
     * @return QtFaststart
     */
    public static function create($configuration = array(), LoggerInterface $logger = null)
    {
        return new static(QtFaststartDriver::create($logger, $configuration));
    }
}
