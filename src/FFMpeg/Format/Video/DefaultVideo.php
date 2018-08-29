<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

use FFMpeg\FFProbe;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\MediaTypeInterface;
use FFMpeg\Format\ProgressListener\VideoProgressListener;

/**
 * The abstract default Video format
 */
abstract class DefaultVideo extends DefaultAudio implements VideoInterface
{

    /**
     * @var string
     */
    protected $videoCodec;

    /**
     * @var int
     */
    protected $kiloBitrate = 1000;

    /**
     * @var int
     */
    protected $modulus = 16;

    /**
     * @var string[]
     */
    protected $additionalParamaters;

    /**
     * @inheritDoc
     */
    public function getKiloBitrate() : int
    {
        return $this->kiloBitrate;
    }

    /**
     * Sets the kiloBitrate value.
     *
     * @param  int $kiloBitrate
     * @return self
     * @throws InvalidArgumentException
     */
    public function setKiloBitrate(int $kiloBitrate) : self
    {
        if ($kiloBitrate < 1) {
            throw new InvalidArgumentException('Wrong kiloBitrate value');
        }

        $this->kiloBitrate = (int)$kiloBitrate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVideoCodec() : ? string
    {
        return $this->videoCodec;
    }

    /**
     * Sets the video codec, Should be in the available ones, otherwise an
     * exception is thrown.
     *
     * @param  string $videoCodec
     * @return self
     * @throws InvalidArgumentException
     */
    public function setVideoCodec(string $videoCodec) : self
    {
        if (!in_array($videoCodec, $this->getAvailableVideoCodecs())) {
            throw new InvalidArgumentException(
                sprintf(
                    'Wrong videocodec value for %s, available formats are %s',
                    $videoCodec,
                    implode(', ', $this->getAvailableVideoCodecs())
                )
            );
        }

        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return int
     */
    public function getModulus() : int
    {
        return $this->modulus;
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalParameters() : ? array
    {
        return $this->additionalParamaters;
    }

    /**
     * Sets additional parameters.
     *
     * @param  string[] $additionalParamaters
     * @return self
     * @throws InvalidArgumentException
     */
    public function setAdditionalParameters(array $additionalParamaters) : self
    {
        $this->additionalParamaters = $additionalParamaters;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createProgressListener(
        MediaTypeInterface $media,
        FFProbe $ffprobe,
        int $passes,
        int $totalPasses,
        int $duration = 0
    ) : array {
        $format = $this;
        $listener = new VideoProgressListener($ffprobe, $media->getPathfile(), $passes, $totalPasses, $duration);

        $listener->on(
            'progress',
            function () use ($format, $media) {
                $format->emit('progress', array_merge([$media, $format], func_get_args()));
            }
        );

        return [$listener];
    }
}
