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

use \Symfony\Component\Process\ExecutableFinder;

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
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * Binary constructor
     *
     * @param type            $binary The path file to the binary
     * @param \Monolog\Logger $logger A logger
     */
    public function __construct($binary, \Monolog\Logger $logger = null)
    {
        $this->binary = $binary;

        if ( ! $logger) {
            $logger = new \Monolog\Logger('default');
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this->logger = $logger;
    }

    /**
     * Load the static binary
     *
     * @param  \Monolog\Logger                   $logger A logger
     * @return \FFMpeg\Binary                    The binary
     * @throws Exception\BinaryNotFoundException
     */
    public static function load(\Monolog\Logger $logger = null)
    {
        $finder = new ExecutableFinder();
        $binary = null;

        foreach (static::getBinaryName() as $candidate) {
            if (null !== $binary = $finder->find($candidate)) {
                break;
            }
        }

        if (null === $binary) {
            throw new Exception\BinaryNotFoundException('Binary not found');
        }

        return new static($binary, $logger);
    }

    /**
     * Return the binary name
     *
     * @throws \Exception
     */
    protected static function getBinaryName()
    {
        throw new \Exception('Should be implemented');
    }
}
