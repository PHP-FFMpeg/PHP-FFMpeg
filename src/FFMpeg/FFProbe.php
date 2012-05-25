<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use \Symfony\Component\Process\Process;

/**
 * FFProbe driver
 *
 * @author Romain Neutron imprec@gmail.com
 */
class FFProbe extends Binary
{

    /**
     * Probe the format of a given file
     *
     * @param  string                                 $pathfile
     * @return string
     * @throws Exception\InvalidFileArgumentException
     * @throws Exception\RuntimeException
     */
    public function probeFormat($pathfile)
    {
        if ( ! is_file($pathfile)) {
            throw new Exception\InvalidFileArgumentException($pathfile);
        }

        $cmd = $this->binary . ' ' . $pathfile . ' -show_format';

        return $this->executeProbe($cmd);
    }

    /**
     * Probe the streams contained in a given file
     *
     * @param  string                                 $pathfile
     * @return string
     * @throws Exception\InvalidFileArgumentException
     * @throws Exception\RuntimeException
     */
    public function probeStreams($pathfile)
    {
        if ( ! is_file($pathfile)) {
            throw new Exception\InvalidFileArgumentException($pathfile);
        }

        $cmd = $this->binary . ' ' . $pathfile . ' -show_streams';

        return $this->executeProbe($cmd);
    }

    /**
     *
     * @param  string                     $command
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function executeProbe($command)
    {
        try {
            $process = new Process($command);

            $process->run();
        } catch (\RuntimeException $e) {
            throw new Exception\RuntimeException(sprintf('Failed to run the given command %s', $command));
        }

        if ( ! $process->isSuccessful()) {
            throw new Exception\RuntimeException(sprintf('Failed to probe %s', $command));
        }

        return $process->getOutput();
    }

    /**
     * Return the binary name
     *
     * @return string
     */
    protected static function getBinaryName()
    {
        return array('avprobe', 'ffprobe');
    }
}
