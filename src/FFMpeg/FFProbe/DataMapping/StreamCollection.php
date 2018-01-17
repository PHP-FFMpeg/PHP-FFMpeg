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
     * Holds the audio streams
     * @var Stream[]
     */
    private $audioStreams;

    /**
     * Holds the video streams
     * @var Stream[]
     */
    private $videoStreams;

    /**
     * Holds the unique streams
     *
     * @var Stream[]
     */
    private $uniqueStreams;

    /**
     * Creates a new collection of streams
     *
     * @param   Stream[]   $streams
     */
    public function __construct(array $streams = []) {
        $this->streams = array_values($streams);
    }

    /**
     * Resets the cached mapping of streams
     *
     * @return void
     */
    protected function resetStreamMapping(): void {
        $this->audioStreams = null;
        $this->videoStreams = null;
        $this->uniqueStreams = null;
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

        // clear cache
        $this->resetStreamMapping();

        return $this;
    }

    /**
     * Returns a new StreamCollection with unique streams
     *
     * @return StreamCollection
     * @since 1.0.0
     */
    public function unique() {
        if($this->uniqueStreams === null) {
            $this->uniqueStreams = [];

            $serializedStreams = array_map(function(Stream $stream) {
                return serialize($stream);
            }, $this->streams);

            $serializedStreams = array_unique($serializedStreams);

            $this->uniqueStreams = array_map(function(string $stream) {
                return unserialize($stream);
            }, $serializedStreams);
        }


        return new static($uniqueStreams);
    }

    /**
     * Returns a new StreamCollection with only video streams.
     *
     * @return StreamCollection
     * @since 1.0.0
     */
    public function getVideoStreams(): StreamCollection {
        if($this->videoStreams === null) {
            $this->videoStreams = [];
            $this->videoStreams = array_filter($this->streams, function (Stream $stream) {
                return $stream->isVideo();
            });
        }

        return new static($this->videoStreams);
    }

    /**
     * Returns a new StreamCollection with only video streams.
     *
     * @return StreamCollection
     * @deprecated 1.0.0 use `getVideoStreams` instead
     */
    public function videos(): StreamCollection {
        return $this->getVideoStreams();
    }

    /**
     * Returns a new StreamCollection with only audio streams.
     *
     * @return StreamCollection
     */
    public function getAudioStreams(): StreamCollection {
        if($this->audioStreams === null) {
            $this->audioStreams = [];
            $this->audioStreams = array_filter($this->streams, function (Stream $stream) {
                return $stream->isAudio();
            });
        }

        return new static($this->audioStreams);
    }

    /**
     * Returns a new StreamCollection with only audio streams.
     *
     * @return StreamCollection
     * @deprecated 1.0.0 use `getAudioStreams` instead
     */
    public function audios(): StreamCollection {
        return $this->getAudioStreams();
    }

    /**
     * @inheritDoc
     */
    public function count(): int {
        return count($this->streams);
    }

    /**
     * Returns the array of contained streams.
     *
     * @return Stream[]
     */
    public function all(): array {
        return $this->streams;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \ArrayIterator {
        return new \ArrayIterator($this->streams);
    }
}
