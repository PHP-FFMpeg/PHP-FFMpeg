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
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  A format array or JSON-string
     *
     * @throws InvalidArgumentException
     */
    public function probeFormat($pathfile, $toArray = false)
    {
        if ( ! is_file($pathfile)) {
            throw new InvalidArgumentException($pathfile);
        }

        if (isset($this->cachedFormats[$pathfile . $toArray])) {
            return $this->cachedFormats[$pathfile . $toArray];
        }

        // If option 'print_format' is available then parse JSON output and return result
        if ($this->checkOption('print_format')) {
            return $this->parseFormatFromJson($pathfile, $toArray);
        }

        // ... else parse plain output and return output
        return $this->parseFormatFromPlain($pathfile, $toArray);
    }

    /**
     * Parse format from JSON output
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  A format array or JSON-string
     */
    protected function parseFormatFromJson($pathfile, $toArray)
    {
        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-loglevel', 'quiet', '-print_format', 'json', '-show_format'
        ));

        // Decode JSON output
        $output = json_decode($this->executeProbe($builder->getProcess()), true);
        $data = $output['format'];

        // Convert returned data to JSON if it needs
        if ($toArray === false) {
            $data = json_encode($data);
        }

        return $this->cachedFormats[$pathfile . $toArray] = $data;
    }

    /**
     * Parse format from plain output
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  A format array or JSON-string
     */
    protected function parseFormatFromPlain($pathfile, $toArray)
    {
        // Create process builder
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

        // Convert returned data to JSON if it needs
        if ($toArray === false) {
            $ret = json_encode($ret);
        }

        return $this->cachedFormats[$pathfile . $toArray] = $ret;
    }

    /**
     * Probe the streams contained in a given file
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  An array of streams array or JSON-string
     *
     * @throws InvalidArgumentException
     */
    public function probeStreams($pathfile, $toArray = false)
    {
        if ( ! is_file($pathfile)) {
            throw new InvalidArgumentException($pathfile);
        }

        // If option 'print_format' is available then parse JSON output and return result
        if ($this->checkOption('print_format')) {
            return $this->parseStreamsFromJson($pathfile, $toArray);
        }

        // ... else parse plain output and return output
        return $this->parseStreamsFromPlain($pathfile, $toArray);
    }

    /**
     * Parse streams from JSON output
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  An array of streams array or JSON-string
     */
    protected function parseStreamsFromJson($pathfile, $toArray)
    {
        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-loglevel', 'quiet', '-print_format', 'json', '-show_streams'
        ));

        // Decode JSON output
        $output = json_decode($this->executeProbe($builder->getProcess()), true);
        $data = $output['streams'];

        // Convert returned data to JSON if it needs
        if ($toArray === false) {
            $data = json_encode($data);
        }

        return $data;
    }

    /**
     * Parse streams from plain output
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  An array of streams array or JSON-string
     */
    protected function parseStreamsFromPlain($pathfile, $toArray)
    {
        // Create process builder
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

        // Convert returned data to JSON if it needs
        if ($toArray === false) {
            $ret = json_encode(array_values($ret));
        }

        return $ret;
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
