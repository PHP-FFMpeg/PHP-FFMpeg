<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Driver\FFProbeDriver;
use FFMpeg\Exception\RuntimeException;
use Psr\SimpleCache\CacheInterface;

class OptionsTester implements OptionsTesterInterface
{

    /**
     * @var FFProbeDriver
     */
    private $ffprobe;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * The cache key used for the (parsed) output of `ffprobe -help -loglevel quiet`
     */
    protected const HELP_OUTPUT_CACHE_ID = 'php-ffmpeg-ffprobe-helpOutput';

    public function __construct(FFProbeDriver $ffprobe, CacheInterface $cache)
    {
        $this->ffprobe = $ffprobe;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function has(string $optionName) : bool
    {
        $id = sprintf('php-ffmpeg-ffprobe-option-%s', $optionName);

        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        $output = $this->retrieveHelpOutput();

        $ret = (bool)preg_match('/^' . $optionName . '/m', $output);

        $this->cache->set($id, $ret);

        return $ret;
    }

    /**
     * Returns the output of `ffprobe -help -loglevel quiet`.
     *
     * @return string
     */
    private function retrieveHelpOutput() : string
    {
        if ($this->cache->has(static::HELP_OUTPUT_CACHE_ID)) {
            return $this->cache->get(static::HELP_OUTPUT_CACHE_ID);
        }

        try {
            $output = $this->ffprobe->command(['-help', '-loglevel', 'quiet']);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException(
                'Your FFProbe version is too old and does not support `-help` option, please upgrade.',
                $e->getCode(),
                $e
            );
        }

        $this->cache->set(static::HELP_OUTPUT_CACHE_ID, $output);

        return $output;
    }
}
