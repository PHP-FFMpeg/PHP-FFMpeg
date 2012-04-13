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

use FFMpeg\Format\DefaultAudioFormat;

class Mp3 extends DefaultAudioFormat
{

    protected $audioCodec = 'libmp3lame';

    protected function getAvailableAudioCodecs()
    {
        return array('libmp3lame');
    }

}