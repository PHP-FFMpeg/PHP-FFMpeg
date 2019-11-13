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

class FrameCollection implements \Countable, \IteratorAggregate
{
    private $frames;

    public function __construct(array $frames = array())
    {
        $this->frames = array_values($frames);
    }

    /**
     * Returns the first frame of the collection, null if the collection is
     * empty.
     *
     * @return null|Frame
     */
    public function first()
    {
        $frame = reset($this->frames);

        return $frame ?: null;
    }

    /**
     * Returns the last frame of the collection, null if the collection is
     * empty.
     *
     * @return null|Frame
     */
    public function last()
    {
        $frame = end($this->frames);

        return $frame ?: null;
    }

    /**
     * Adds a frame to the collection.
     *
     * @param Frame $frame
     *
     * @return FrameCollection
     */
    public function add(Frame $frame)
    {
        $this->frames[] = $frame;

        return $this;
    }

    /**
     * Returns a new FrameCollection with only video frames.
     *
     * @return FrameCollection
     */
    public function videos()
    {
        return new static(array_filter($this->frames, function (Frame $frame) {
            return $frame->isVideo();
        }));
    }

    /**
     * Returns a new FrameCollection with only audio frames.
     *
     * @return FrameCollection
     */
    public function audios()
    {
        return new static(array_filter($this->frames, function (Frame $frame) {
            return $frame->isAudio();
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->frames);
    }

    /**
     * Returns the array of contained frames.
     *
     * @return array
     */
    public function all()
    {
        return $this->frames;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->frames);
    }
}
