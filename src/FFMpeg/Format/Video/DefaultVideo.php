<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\ProgressListener\VideoProgressListener;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\MediaTypeInterface;

/**
 * The abstract default Video format
 */
abstract class DefaultVideo extends DefaultAudio implements VideoInterface
{
    /** @var string */
    protected $videoCodec;

    /** @var int */
    protected $kiloBitrate = 1000;

    /** @var int */
    protected $modulus = 16;

    /** @var string[] */
    protected $additionalParamaters;

    /**
     * @inheritDoc
     */
    public function getKiloBitrate()
    {
        return $this->kiloBitrate;
    }

    /**
     * Sets the kiloBitrate value.
     *
     * @param  int                  $kiloBitrate
     * @throws InvalidArgumentException
     */
    public function setKiloBitrate(int $kiloBitrate)
    {
        if ($kiloBitrate <= 0) {
            throw new InvalidArgumentException('Kilobitrate must be a positive value!');
        }

        $this->kiloBitrate = (int) $kiloBitrate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * Sets the video codec. Should be in the available ones, otherwise an
     * exception is thrown.
     *
     * @param  string                   $videoCodec
     * @throws InvalidArgumentException
     */
    public function setVideoCodec($videoCodec)
    {
        if (!in_array($videoCodec, $this->getAvailableVideoCodecs())) {
            throw new InvalidArgumentException(sprintf(
                'The given video codec %s is not available for this format, available formats are %s.',
                $videoCodec,
                implode(', ', $this->getAvailableVideoCodecs())
            ));
        }

        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return int
     */
    public function getModulus(): int
    {
        return $this->modulus;
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalParameters()
    {
        return $this->additionalParamaters;
    }

    /**
     * Sets additional parameters.
     *
     * @param  string[] $additionalParamaters
     * @throws InvalidArgumentException
     * @return DefaultVideo
     */
    public function setAdditionalParameters(array $additionalParamaters): DefaultVideo
    {
        $this->additionalParamaters = $additionalParamaters;

        return $this;
    }

    /**
     * @inheritDoc
     * @return VideoProgressListener[]
     */
    public function createProgressListener(MediaTypeInterface $media, FFProbe $ffprobe, $pass, $total, $duration = 0): array
    {
        $format = $this;
        $listener = new VideoProgressListener($ffprobe, $media->getPathfile(), $pass, $total, $duration);
        $listener->on('progress', function () use ($format, $media) {
            $format->emit('progress', array_merge([$media, $format], func_get_args()));
        });

        return [$listener];
    }
}
