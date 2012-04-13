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

abstract class Binary implements AdapterInterface
{

    protected $binary;

    /**
     *
     * @var \Monolog\Logger
     */
    protected $logger;

    public function __construct($binary, $logger = null)
    {
        $this->binary = $binary;

        if ( ! $logger)
        {
            $logger = new \Monolog\Logger('default');
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this->logger = $logger;
    }

    public static function load(\Monolog\Logger $logger = null)
    {
        $finder = new \Symfony\Component\Process\ExecutableFinder();

        if (null === $binary = $finder->find(static::getBinaryName()))
        {
            throw new Exception\BinaryNotFoundException('Binary not found');
        }

        return new static($binary, $logger);
    }

    protected static function run($command, $bypass_errors = false)
    {
        $process = new \Symfony\Component\Process\Process($command);
        $process->run();

        if ( ! $process->isSuccessful() && ! $bypass_errors)
        {
            throw new \RuntimeException('Failed to execute ' . $command);
        }

        $result = $process->getOutput();
        unset($process);

        return $result;
    }

    protected static function getBinaryName()
    {
        throw new Exception('Should be implemented');
    }

}