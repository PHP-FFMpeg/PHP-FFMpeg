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
use FFMpeg\Format\Profile;
use FFMpeg\Format\ProgressListener\VideoProgressListener;

/**
 * The abstract default Video format
 */
abstract class DefaultVideo extends DefaultAudio implements VideoInterface
{
    /** @var string */
    protected $videoCodec;

    /** @var Integer */
    protected $kiloBitrate = 1000;

    /** @var Integer */
    protected $modulus = 16;

    /** @var string */
    private $profile = Profile::MAIN;

    /** @var float */
    private $level = 3.1;

    /** @var string[] */
    protected $additionalParameters;

    /**
     * Sets the profile of this video
     * @var string  $profile    must be one of `baseline`, `main` or `high`
     * @throws \InvalidArgumentException
     */
    public function setProfile(string $profile) {
        switch($profile) {
            case Profile::BASELINE:
            case Profile::MAIN:
            case Profile::HIGH:
            // these are fine
            break;
            default:
                throw new \InvalidArgumentException('Invalid profile given! Must be one of `baseline`, `main` or `high`!');
        }
        $this->profile = $profile;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getProfile() {
        return $this->profile;
    }

    /**
     * Sets the given level
     * @param   float   $level  The level(for example: 3.0, 3.1, 4.0, 4.1)
     */
    public function setLevel(float $level) {
        $this->level = $level;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     */
    public function getKiloBitrate()
    {
        return $this->kiloBitrate;
    }

    /**
     * Sets the kiloBitrate value.
     *
     * @param  integer                  $kiloBitrate
     * @throws InvalidArgumentException
     */
    public function setKiloBitrate($kiloBitrate)
    {
        if ($kiloBitrate < 1) {
            throw new InvalidArgumentException('Wrong kiloBitrate value');
        }

        $this->kiloBitrate = (int) $kiloBitrate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * Sets the video codec, Should be in the available ones, otherwise an
     * exception is thrown.
     *
     * @param  string                   $videoCodec
     * @throws InvalidArgumentException
     */
    public function setVideoCodec($videoCodec)
    {
        if ( ! in_array($videoCodec, $this->getAvailableVideoCodecs())) {
            throw new InvalidArgumentException(sprintf(
                    'Wrong videocodec value for %s, available formats are %s'
                    , $videoCodec, implode(', ', $this->getAvailableVideoCodecs())
            ));
        }

        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return integer
     */
    public function getModulus()
    {
        return $this->modulus;
    }

    /**
     * {@inheritdoc}
     */
    public function createProgressListener(MediaTypeInterface $media, FFProbe $ffprobe, $pass, $total)
    {
        $format = $this;
        $listeners = array(new VideoProgressListener($ffprobe, $media->getPathfile(), $pass, $total));

        foreach ($listeners as $listener) {
            $listener->on('progress', function () use ($format, $media) {
               $format->emit('progress', array_merge(array($media, $format), func_get_args()));
            });
        }

        return $listeners;
    }
}
