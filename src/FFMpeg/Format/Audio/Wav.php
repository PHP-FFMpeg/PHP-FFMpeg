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

/**
 * The WAV audio format.
 */
class Wav extends DefaultAudio
{
    /**
     * The bit depth to use for WAV encoding.
     * Defaults to 16-bit.
     *
     * @var int
     */
    protected int $bitDepth = 16;

    public function __construct()
    {
        $this->audioCodec = 'pcm_s16le';
    }

    /**
     * Return a list of supported audio codecs for WAV.
     *
     * @return array
     */
    public function getAvailableAudioCodecs(): array
    {
        return ['pcm_s16le', 'pcm_s24le', 'pcm_s32le'];
    }

    /**
     * Get the current bit depth for WAV encoding.
     *
     * @return int
     */
    public function getBitDepth(): int
    {
        return $this->bitDepth;
    }

    /**
     * Set the bit depth for WAV encoding.
     *
     * Allowed values: 16, 24, 32.
     *
     * @param int $bitDepth
     * @return $this
     * @throws \InvalidArgumentException if the provided bit depth is unsupported.
     */
    public function setBitDepth(int $bitDepth): static
    {
        if (! in_array($bitDepth, [16, 24, 32])) {
            throw new \InvalidArgumentException('Unsupported bit depth. Allowed values are 16, 24, or 32.');
        }
        $this->bitDepth = $bitDepth;

        switch ($bitDepth) {
            case '16':
                $this->audioCodec = 'pcm_s16le';
                break;
            case '24':
                $this->audioCodec = 'pcm_s24le';
                break;
            case '32':
                $this->audioCodec = 'pcm_s32le';
                break;
        }

        return $this;
    }

}
