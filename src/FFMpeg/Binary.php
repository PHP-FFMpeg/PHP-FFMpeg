<?php

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
        if ('' === $binary = self::autodetect(static::getBinaryName()))
        {
            throw new \Exception('Binary not found');
        }

        return new static($binary, $logger);
    }

    protected static function run($command, $bypass_errors = false)
    {
        $process = new \Symfony\Component\Process\Process($command);
        $process->run();

        if ( ! $process->isSuccessful() && ! $bypass_errors)
        {
            throw new Exception\RuntimeException('Failed to execute ' . $command);
        }

        $result = $process->getOutput();
        unset($process);

        return $result;
    }

    /**
     * Autodetect the presence of a binary
     *
     * @param   string      $binaryName
     * @return  string
     */
    protected static function autodetect($binaryName)
    {
        return trim(self::run(sprintf('which %s', escapeshellarg($binaryName)), true));
    }

    protected static function getBinaryName()
    {
        throw new Exception('Should be implemented');
    }

}