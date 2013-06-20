<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

interface AudioFilterInterface extends FilterInterface
{
    public function apply(Audio $audio, AudioInterface $format);
}
