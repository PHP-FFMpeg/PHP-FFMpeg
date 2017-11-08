<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe\DataMapping;

/**
 * Represents a collection of streams
 */
class StreamCollection implements \Countable, \IteratorAggregate {

    /**
     * Holds the streams
     * @var Stream[]
     */
    private $streams;

    /**
     * Creates a new collection of streams
     *
     * @param   Stream[]   $streams
     */
    public function __construct(array $streams = []) {
        $this->streams = array_values($streams);
    }

    /**
     * Returns the first stream of the collection, null if the collection is
     * empty.
     *
     * @return Stream|null
     */
    public function first() {
        $stream = reset($this->streams);

        return $stream ?: null;
    }

    /**
     * Adds a stream to the collection.
     *
     * @param   Stream  $stream
     * @return StreamCollection
     */
    public function add(Stream $stream) {
        $this->streams[] = $stream;

        return $this;
    }

    /**
     * Returns a new StreamCollection with unique streams
     *
     * @return StreamCollection
     * @since 1.0.0
     */
    public function unique() {
        $serializedStreams = array_map(function(Stream $stream) {
            return serialize($stream);
        }, $this->streams);

        $serializedStreams = array_unique($serializedStreams);

        $uniqueStreams = array_map(function(string $stream) {
            return unserialize($stream);
        }, $serializedStreams);

        return new static($uniqueStreams);
    }

    /**
     * Returns a new StreamCollection with only video streams.
     *
     * @return StreamCollection
     */
    public function videos() {
        return new static(array_filter($this->streams, function (Stream $stream) {
            return $stream->isVideo();
        }));
    }

    /**
     * Returns a new StreamCollection with only audio streams.
     *
     * @return StreamCollection
     */
    public function audios() {
        return new static(array_filter($this->streams, function (Stream $stream) {
            return $stream->isAudio();
        }));
    }

    /**
     * @inheritDoc
     */
    public function count() {
        return count($this->streams);
    }

    /**
     * Returns the array of contained streams.
     *
     * @return Stream[]
     */
    public function all() {
        return $this->streams;
    }

    /**
     * @inheritDoc
     */
    public function getIterator() {
        return new \ArrayIterator($this->streams);
    }
}
