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
use Symfony\Component\Process\ProcessBuilder;

/**
 * FFProbe driver
 *
 * @author Romain Neutron imprec@gmail.com
 */
class FFProbe extends Binary
{

    protected $cachedFormats = array();

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

        if (isset($this->cachedFormats[$pathfile])) {
            return $this->cachedFormats[$pathfile];
        }

        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-show_format'
        ));

        $output = $this->executeProbe($builder->getProcess());

        $ret = array();

        foreach (explode(PHP_EOL, $output) as $line) {

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

        return $this->cachedFormats[$pathfile] = json_encode($ret);
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

        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-show_streams'
        ));

        $output = explode(PHP_EOL, $this->executeProbe($builder->getProcess()));

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
     * @param  Process          $process
     * @return string
     * @throws RuntimeException
     */
    protected function executeProbe(Process $process)
    {
        $this->logger->addInfo(sprintf('FFprobe executes command %s', $process->getCommandline()));

        try {
            $process->run();
        } catch (\RuntimeException $e) {
            $this->logger->addInfo('FFprobe command failed');

            throw new RuntimeException(sprintf('Failed to run the given command %s', $process->getCommandline()));
        }

        if ( ! $process->isSuccessful()) {
            $this->logger->addInfo('FFprobe command failed');

            throw new RuntimeException(sprintf('Failed to probe %s', $process->getCommandline()));
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
