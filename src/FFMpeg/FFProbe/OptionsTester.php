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
use Psr\Cache\CacheItemPoolInterface;

class OptionsTester implements OptionsTesterInterface
{
    /** @var FFProbeDriver */
    private $ffprobe;
    /** @var CacheItemPoolInterface */
    private $cache;

    public function __construct(FFProbeDriver $ffprobe, CacheItemPoolInterface $cache)
    {
        $this->ffprobe = $ffprobe;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        $id = md5(sprintf('option-%s', $name));

        if ($this->cache->hasItem($id)) {
            return $this->cache->getItem($id)->get();
        }

        $output = $this->retrieveHelpOutput();

        $ret = (bool) preg_match('/^'.$name.'/m', $output);

        $cacheItem = $this->cache->getItem($id);
        $cacheItem->set($ret);
        $this->cache->save($cacheItem);

        return $ret;
    }

    private function retrieveHelpOutput()
    {
        $id = 'help';

        if ($this->cache->hasItem($id)) {
            return $this->cache->getItem($id)->get();
        }

        try {
            $output = $this->ffprobe->command(['-help', '-loglevel', 'quiet']);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('Your FFProbe version is too old and does not support `-help` option, please upgrade.', $e->getCode(), $e);
        }

        $cacheItem = $this->cache->getItem($id);
        $cacheItem->set($output);
        $this->cache->save($cacheItem);

        return $output;
    }
}
