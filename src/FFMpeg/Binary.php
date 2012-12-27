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
use Symfony\Component\Process\Process;
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
     * @var array    Cached binary options
     */
    protected $cachedOptions = array();

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
     *
     * @param  Process          $process
     * @return string
     * @throws RuntimeException
     */
    protected function execute(Process $process) {
        $this->logger->addInfo(sprintf($this->binary . ' executes command %s', $process->getCommandline()));

        try {
            $process->run();
        } catch (\RuntimeException $e) {

        }

        if ( ! $process->isSuccessful()) {
            $this->logger->addInfo($this->binary . ' command failed');

            throw new RuntimeException(sprintf('Failed to run the given command %s', $process->getCommandline()));
        }

        $this->logger->addInfo($this->binary . ' command successful');

        return $process->getOutput();
    }

    /**
     * Check binary option
     *
     * @param string $option  Option
     * @return boolean
     */
    protected function checkOption($option)
    {
        // If option is in the cache then return true
        if (isset($this->cachedOptions[$option])) {
            return true;
        }
        // ... else if cache is not empty then return false
        elseif (count($this->cachedOptions)) {
            return false;
        }

        // Create process builder
        $builder = ProcessBuilder::create(array(
            $this->binary, '-help', '-loglevel', 'quiet'
        ));

        // Execute process & fetch its output
        $output = explode(PHP_EOL, $this->execute($builder->getProcess()));

        // Search first occurence of substring of 'AVOptions'
        $index = $this->arraySearchSubstr($output, 'AVOptions');

        /*
         * If the substring is found, then cut off the unnecessary part of the output array
         * (we need only main options!)
         */
        if ($index != null) {
            array_splice($output, $index);
        }

        // Filter and get only options' names from the output array
        $options = preg_filter('/^\-{1,2}([a-z0-9_\?]+)\s+.*$/i', '$1', $output);

        // Save options in cache and find the option
        $this->cachedOptions = array_fill_keys(array_unique($options), 1);

        return isset($this->cachedOptions[$option]);
    }

    /**
     * Find substring in values of an array
     *
     * @param array  $haystack  An array for search
     * @param string $needle    Searching substring
     * @return null|integer
     */
    private function arraySearchSubstr($haystack, $needle)
    {
        $aIt = new \ArrayObject($haystack);
        $it  = $aIt->getIterator();
        $resultkey = null;

        while($it->valid()) {
            if (strpos($it->current(), $needle) !== false) {
                $resultkey = $it->key();
                break;
            }

            $it->next();
        }

        return $resultkey;
    }

    /**
     * Return the binary name
     */
    protected static function getBinaryName()
    {
        throw new \Exception('Should be implemented');
    }
}
