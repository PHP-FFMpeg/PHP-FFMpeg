<?php
declare (strict_types = 1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use Alchemy\BinaryDriver\ConfigurationInterface;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Driver\FFProbeDriver;
use FFMpeg\FFProbe\DataMapping\Format;
use FFMpeg\FFProbe\Mapper;
use FFMpeg\FFProbe\MapperInterface;
use FFMpeg\FFProbe\CodecTester;
use FFMpeg\FFProbe\CodecTesterInterface;
use FFMpeg\FFProbe\OptionsTester;
use FFMpeg\FFProbe\OptionsTesterInterface;
use FFMpeg\FFProbe\OutputParser;
use FFMpeg\FFProbe\OutputParserInterface;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use Sabre\Cache\Memory as MemoryCache;

class FFProbe
{
    const TYPE_STREAMS = 'streams';
    const TYPE_FORMAT = 'format';

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var OptionsTesterInterface
     */
    private $optionsTester;

    /**
     * @var OutputParserInterface
     */
    private $parser;

    /**
     * @var FFProbeDriver
     */
    private $ffprobe;

    /**
     * @var MapperInterface
     */
    private $mapper;

    /**
     * Checks whether codecs are available
     *
     * @var CodecTesterInterface
     */
    private $codecTester;

    public function __construct(FFProbeDriver $ffprobe, CacheInterface $cache)
    {
        $this->ffprobe = $ffprobe;
        $this->codecTester = new CodecTester($ffprobe, $cache);
        $this->optionsTester = new OptionsTester($ffprobe, $cache);
        $this->parser = new OutputParser();
        $this->mapper = new Mapper();
        $this->cache = $cache;
    }

    /**
     * @return OutputParserInterface
     */
    public function getParser() : OutputParserInterface
    {
        return $this->parser;
    }

    /**
     * @param OutputParserInterface $parser
     *
     * @return FFProbe
     */
    public function setParser(OutputParserInterface $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return FFProbeDriver
     */
    public function getFFProbeDriver() : FFProbeDriver
    {
        return $this->ffprobe;
    }

    /**
     * @param FFProbeDriver $ffprobe
     *
     * @return self
     */
    public function setFFProbeDriver(FFProbeDriver $ffprobe) : self
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }


    /**
     * @param CodecTesterInterface $tester
     *
     * @return self
     * @since 1.0.0
     */
    public function setCodecTester(CodecTesterInterface $tester) : self
    {
        $this->codecTester = $tester;

        return $this;
    }

    /**
     * @return CodecTesterInterface
     * @since 1.0.0
     */
    public function getCodecTester() : CodecTesterInterface
    {
        return $this->codecTester;
    }

    /**
     * @param OptionsTesterInterface $tester
     *
     * @return self
     */
    public function setOptionsTester(OptionsTesterInterface $tester) : self
    {
        $this->optionsTester = $tester;

        return $this;
    }

    /**
     * @return OptionsTesterInterface
     */
    public function getOptionsTester() : OptionsTesterInterface
    {
        return $this->optionsTester;
    }

    /**
     * @param CacheInterface $cache
     *
     * @return FFProbe
     */
    public function setCache(CacheInterface $cache) : FFProbe
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return CacheInterface
     */
    public function getCache() : CacheInterface
    {
        return $this->cache;
    }

    /**
     * @return MapperInterface
     */
    public function getMapper() : MapperInterface
    {
        return $this->mapper;
    }

    /**
     * @param MapperInterface $mapper
     *
     * @return FFProbe
     */
    public function setMapper(MapperInterface $mapper) : FFProbe
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * Probes the format of a given file.
     *
     * @param string $pathfile
     *
     * @return Format
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function format(string $pathfile) : Format
    {
        return $this->probe($pathfile, '-show_format', static::TYPE_FORMAT);
    }

    /**
     * Checks wether the given `$pathfile` is considered a valid media file.
     *
     * @param  string $pathfile
     * @return bool
     * @since  0.10.0
     */
    public function isValid($pathfile) : bool
    {
        try {
            return $this->format($pathfile)->get('duration') > 0;
        } catch (\Throwable $e) {
            // complete invalid data
            return false;
        }
    }

    /**
     * Probes the streams contained in a given file.
     *
     * @param string $pathfile
     *
     * @return StreamCollection A collection of streams
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function streams($pathfile) : StreamCollection
    {
        return $this->probe($pathfile, '-show_streams', static::TYPE_STREAMS);
    }

    /**
     * Creates an FFProbe.
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface              $logger
     * @param CacheInterface               $cache
     *
     * @return FFProbe
     */
    public static function create(
        $configuration = [],
        LoggerInterface $logger = null,
        CacheInterface $cacheDriver = null
    ) {
        if (null === $cacheDriver) {
            // default to in-memory cache
            $cacheDriver = new MemoryCache();
        }

        return new static(FFProbeDriver::create($configuration, $logger), $cacheDriver);
    }

    private function probe($pathfile, $command, $type, $allowJson = true)
    {
        $id = sprintf('%s-%s', $command, $pathfile);

        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        if (!$this->optionsTester->has($command)) {
            throw new RuntimeException(
                sprintf(
                    'This version of ffprobe is too old and '
                        . 'does not support `%s` option, please upgrade',
                    $command
                )
            );
        }

        $commands = [$pathfile, $command];

        $parseIsToDo = false;

        if ($allowJson && $this->optionsTester->has('-print_format')) {
            // allowed in latest ffmpeg version
            $commands[] = '-print_format';
            $commands[] = 'json';
        } else {
            $parseIsToDo = true;
        }

        try {
            $output = $this->ffprobe->command($commands);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException(sprintf('Unable to probe %s', $pathfile), $e->getCode(), $e);
        }

        if ($parseIsToDo) {
            $data = $this->parser->parse($type, $output);
        } else {
            try {
                // Malformed json may be retrieved
                $data = $this->parseJson($output);
            } catch (RuntimeException $e) {
                return $this->probe($pathfile, $command, $type, false);
            }
        }

        $ret = $this->mapper->map($type, $data);

        $this->cache->set($id, $ret);

        return $ret;
    }

    /**
     * Returns the php variable representation of a json string
     *
     * @param  string $json The json encoded string to parse
     * @return mixed
     * @throws RuntimeException When invalid json is supplied.
     * @see    json_decode()
     */
    private function parseJson(string $json)
    {
        $ret = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(sprintf('Unable to parse json: %s', json_last_error_msg()));
        }

        return $ret;
    }
}
