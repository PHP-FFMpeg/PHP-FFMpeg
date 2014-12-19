<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Options\Audio;

use FFMpeg\Options\OptionInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

interface AudioOptionInterface extends OptionInterface
{
    /**
     * Applies the option on the the Audio media given an format.
     *
     * @param Audio          $audio
     * @param AudioInterface $format
     *
     * @return array An array of arguments
     */
    public function apply(Audio $audio, AudioInterface $format);
}
