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

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use Symfony\Component\Process\Process;

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
     * @param  string $pathfile
     * @return string A Json object containing the key/values of the probe output
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function probeFormat($pathfile)
    {
        if ( ! is_file($pathfile)) {
            throw new InvalidArgumentException($pathfile);
        }

        $cmd = $this->binary . ' ' . escapeshellarg($pathfile) . ' -show_format';

        $output = $this->executeProbe($cmd);

        $ret = array();

        foreach (explode("\n", $output) as $line) {

            if (in_array($line, array('[FORMAT]', '[/FORMAT]'))) {
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if (ctype_digit($value)) {
                $value = (int) $value;
            }

            $ret[$key] = $value;
        }

        return json_encode($ret);
    }

    /**
     * Probe the streams contained in a given file
     *
     * @param  string $pathfile
     * @return array  An array of streams array
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function probeStreams($pathfile)
    {
        if ( ! is_file($pathfile)) {
            throw new InvalidArgumentException($pathfile);
        }

        $cmd = $this->binary . ' ' . escapeshellarg($pathfile) . ' -show_streams';

        $output = explode("\n", $this->executeProbe($cmd));

        $ret = array();
        $n = 0;

        foreach ($output as $line) {

            if ($line == '[STREAM]') {
                $n ++;
                $ret[$n] = array();
                continue;
            }
            if ($line == '[/STREAM]') {
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if (ctype_digit($value)) {
                $value = (int) $value;
            }

            $ret[$n][$key] = $value;
        }

        return json_encode(array_values($ret));
    }

    /**
     *
     * @param  string           $command
     * @return string
     * @throws RuntimeException
     */
    protected function executeProbe($command)
    {
        $this->logger->addInfo(sprintf('FFprobe executes command %s', $command));

        try {
            $process = new Process($command);

            $process->run();
        } catch (\RuntimeException $e) {
            $this->logger->addInfo('FFprobe command failed');

            throw new RuntimeException(sprintf('Failed to run the given command %s', $command));
        }

        if ( ! $process->isSuccessful()) {
            $this->logger->addInfo('FFprobe command failed');

            throw new RuntimeException(sprintf('Failed to probe %s', $command));
        }

        $this->logger->addInfo('FFprobe command successful');

        return $process->getOutput();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    protected static function getBinaryName()
    {
        return array('avprobe', 'ffprobe');
    }
}
