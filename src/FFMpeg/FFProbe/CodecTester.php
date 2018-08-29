<?php
declare (strict_types = 1);
namespace FFMpeg\FFProbe;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Driver\FFProbeDriver;
use Psr\SimpleCache\CacheInterface;

/**
 * Checks whether specific codecs are available
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2018
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  FFProbe
 * @since       1.0.0
 */
final class CodecTester implements CodecTesterInterface
{

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var FFProbeDriver
     */
    private $ffprobe;

    /**
     * Cache key used for the available codecs
     */
    private const CACHE_KEY = 'php-ffmpeg-available-codecs';

    /**
     * How long the codec cache's ttl is.
     */
    private const CACHE_TTL = 600;

    public function __construct(FFProbeDriver $ffprobe, CacheInterface $cache)
    {
        $this->ffprobe = $ffprobe;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableCodecs() : array
    {
        if ($this->cache->has(self::CACHE_KEY)) {
            return $this->cache->get(self::CACHE_KEY);
        }

        try {
            $buildConfiguration = $this->ffprobe->command(['-buildconf']);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('FFProbe does not support the `-buildconf` option. Please update it.', 0, $e);
        }

        preg_match_all('/--enable-(?P<codec>[_a-z0-9]+)/m', $buildConfiguration, $availableCodecs);

        $this->cache->set(self::CACHE_KEY, $availableCodecs['codec'], self::CACHE_TTL);

        return $availableCodecs['codec'];
    }

    /**
     * @inheritDoc
     */
    public function has(string $codecName) : bool
    {
        return \in_array($codecName, $this->getAvailableCodecs());
    }
}
