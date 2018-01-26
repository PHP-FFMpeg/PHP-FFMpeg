<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format;

/**
 * Default implementation for Video Foramts
 *
 * @author     jens1o
 * @copyright  Jens Hausdorf 2018
 * @license    MIT License
 * @package    FFMpeg
 * @subpackage Format
 */
interface VideoInterface extends AudioInterface
{

    /**
     * Gets the kiloBitrate value.
     *
     * @return int
     */
    public function getKiloBitrate(): int;

    /**
     * Returns the modulus used by the Resizable video.
     *
     * This used to calculate the target dimensions while maintaining the best
     * aspect ratio.
     *
     * @see http://www.undeadborn.net/tools/rescalculator.php
     *
     * @return int
     */
    public function getModulus(): int;

    /**
     * Returns the video codec.
     *
     * @return string|null
     */
    public function getVideoCodec(): ?string;

    /**
     * Returns true if the current format supports B-Frames.
     *
     * @see https://wikipedia.org/wiki/Video_compression_picture_types
     *
     * @return bool
     */
    public function supportBFrames(): bool;

    /**
     * Returns the list of available video codecs for this format.
     *
     * @return string[]
     */
    public function getAvailableVideoCodecs(): array;

    /**
     * Returns the list of additional parameters for this format.
     *
     * @return string[]|null
     */
    public function getAdditionalParameters(): ?array;
}
