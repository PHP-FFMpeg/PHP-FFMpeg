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
        if (PHP_VERSION_ID < 72000) {
            // symfony/cache:4.* does not accept slashes '/' in keys
            $key = sprintf('option-%s', md5($name));
        } else {
            $key = sprintf('option-%s', $name);
        }

        $item = $this->cache->getItem($key);

        if ($item->isHit()) {
            return $item->get();
        }

        $output = $this->retrieveHelpOutput();

        $ret = (bool) preg_match('/^'.$name.'/m', $output);
        $item->set($ret);

        $this->cache->save($item);

        return $ret;
    }

    private function retrieveHelpOutput()
    {
        $item = $this->cache->getItem('help');

        if ($item->isHit()) {
            return $item->get();
        }

        try {
            $output = $this->ffprobe->command(array('-help', '-loglevel', 'quiet'));
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('Your FFProbe version is too old and does not support `-help` option, please upgrade.', $e->getCode(), $e);
        }

        $item->set($output);

        $this->cache->save($item);

        return $output;
    }
}
