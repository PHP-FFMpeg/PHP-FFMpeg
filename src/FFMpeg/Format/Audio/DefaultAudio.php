<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Audio;

use Evenement\EventEmitter;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\MediaTypeInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\ProgressListener\AudioProgressListener;
use FFMpeg\FFProbe;

abstract class DefaultAudio extends EventEmitter implements AudioInterface, ProgressableInterface
{

    /**
     * @var string|null
     */
    protected $audioCodec;

    /**
     * @var int
     */
    protected $audioKiloBitrate = 128;

    /**
     * @var int
     */
    protected $audioChannels;

    /**
     * @inheritDoc
     */
    public function getExtraParams() : array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAudioCodec() : ? string
    {
        return $this->audioCodec;
    }

    /**
     * Sets the audio codec, Should be in the available ones, otherwise an
     * exception is thrown.
     *
     * @param  string $audioCodec
     * @return self
     * @throws InvalidArgumentException
     */
    public function setAudioCodec(string $audioCodec) : self
    {
        if (!in_array($audioCodec, $this->getAvailableAudioCodecs())) {
            throw new InvalidArgumentException(
                sprintf(
                    'Wrong audiocodec value for %s, available formats are %s',
                    $audioCodec,
                    implode(', ', $this->getAvailableAudioCodecs())
                )
            );
        }

        $this->audioCodec = $audioCodec;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAudioKiloBitrate() : int
    {
        return $this->audioKiloBitrate;
    }

    /**
     * Sets the kiloBitrate value.
     *
     * @param  int $kiloBitrate
     * @throws InvalidArgumentException
     * @return self
     */
    public function setAudioKiloBitrate(int $kiloBitrate) : self
    {
        if ($kiloBitrate < 1) {
            throw new InvalidArgumentException('Wrong kiloBitrate value, must be positive');
        }

        $this->audioKiloBitrate = (int)$kiloBitrate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAudioChannels() : ? int
    {
        return $this->audioChannels;
    }

    /**
     * Sets the channels value.
     *
     * @param  int $channels
     * @throws InvalidArgumentException
     * @return self
     */
    public function setAudioChannels(int $channels) : self
    {
        if ($channels < 1) {
            throw new InvalidArgumentException('Wrong channels value, must be positive');
        }

        $this->audioChannels = (int)$channels;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createProgressListener(
        MediaTypeInterface $media,
        FFProbe $ffprobe,
        int $passes,
        int $total,
        int $duration = 0
    ) : array {
        $format = $this;
        $listener = new AudioProgressListener($ffprobe, $media->getPathfile(), $passes, $total, $duration);
        $listener->on('progress', function () use ($media, $format) {
            $format->emit('progress', array_merge([$media, $format], func_get_args()));
        });

        return [$listener];
    }

    /**
     * @inheritDoc
     */
    public function getPasses() : int
    {
        return 1;
    }
}
