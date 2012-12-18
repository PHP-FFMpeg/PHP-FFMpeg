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

use FFMpeg\Exception\BinaryNotFoundException;
use Monolog\Logger;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Binary abstract class
 *
 * @author Romain Neutron imprec@gmail.com
 */
abstract class Binary implements AdapterInterface
{
    protected $binary;

    /**
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Binary constructor
     *
     * @param type   $binary The path file to the binary
     * @param Logger $logger A logger
     *
     * @throws BinaryNotFoundException
     */
    public function __construct($binary, Logger $logger)
    {
        if (!is_executable($binary)) {
            throw new \FFMpeg\Exception\BinaryNotFoundException(sprintf('`%s` is not a valid binary', $binary));
        }

        $this->binary = $binary;
        $this->logger = $logger;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->binary = $this->logger = null;
    }

    /**
     * {@inheritdoc}
     *
     * @param  Logger $logger A logger
     * @return Binary The binary
     *
     * @throws Exception\BinaryNotFoundException
     */
    public static function load(Logger $logger)
    {
        $finder = new ExecutableFinder();
        $binary = null;

        foreach (static::getBinaryName() as $candidate) {
            if (null !== $binary = $finder->find($candidate)) {
                break;
            }
        }

        if (null === $binary) {
            throw new BinaryNotFoundException('Binary not found');
        }

        return new static($binary, $logger);
    }

    /**
     * Check binary option
     *
     * @param string $option
     * @return boolean
     */
    protected function checkOption($option)
    {
        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, '-help', '-loglevel', 'quiet'
        ));

        // Execute process & fetch its output
        $output = explode(PHP_EOL, $this->executeProbe($builder->getProcess()));

        // Search the option at the beginning of one of the line of output array
        $isPrintFormat = false;
        foreach ($output as $line) {
            if (strpos($line, '-' . $option) === 0) {
                $isPrintFormat = true;
                break;
            }
        }

        return $isPrintFormat;
    }

    /**
     * Return the binary name
     */
    protected static function getBinaryName()
    {
        throw new \Exception('Should be implemented');
    }
}
