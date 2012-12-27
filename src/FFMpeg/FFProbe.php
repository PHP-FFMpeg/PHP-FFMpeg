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
            return $this->cachedFormats[$pathfile . $toArray] = $this->parseJson($pathfile, 'show_format', 'format', $toArray);
        }

        // ... else parse plain output and return output
        return $this->parseFormatPlain($pathfile, $toArray);
    }

    /**
     * Parse format plain output
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  A format array or JSON-string
     */
    protected function parseFormatPlain($pathfile, $toArray)
    {
        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-loglevel', 'quiet', '-show_format'
        ));

        $output = $this->execute($builder->getProcess());

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
            return $this->parseJson($pathfile, 'show_streams', 'streams', $toArray);
        }

        // ... else parse plain output and return output
        return $this->parseStreamsPlain($pathfile, $toArray);
    }

    /**
     * Parse streams plain output
     *
     * @param  string $pathfile
     * @param  boolean $toArray If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string  An array of streams array or JSON-string
     */
    protected function parseStreamsPlain($pathfile, $toArray)
    {
        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-loglevel', 'quiet', '-show_streams'
        ));

        $output = explode(PHP_EOL, $this->execute($builder->getProcess()));

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
     * Parse JSON output
     *
     * @param  string $pathfile  Source file
     * @param  string $option    Requested option
     * @param  string $key       The key of the output array
     * @param  boolean $toArray  If the returned value should be an array or the raw JSON output from ffmpeg
     * @return array|string      A format array or JSON-string
     */
    protected function parseJson($pathfile, $option, $key, $toArray)
    {
        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, $pathfile, '-loglevel', 'quiet', '-print_format', 'json', '-' . $option
        ));

        // Decode JSON output
        $output = json_decode($this->execute($builder->getProcess()), true);
        $data = $output[$key];

        // Convert returned data to JSON if it needs
        if ($toArray === false) {
            $data = json_encode($data);
        }

        return $data;
    }

    /**
     *
     * @param  Process          $process
     * @return string
     * @throws RuntimeException
     */
    protected function execute(Process $process)
    {
        $this->logger->addInfo(sprintf('FFprobe executes command %s', $process->getCommandline()));

        try {
            $process->run();
        } catch (\RuntimeException $e) {

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
