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

use FFMpeg\Format\DefaultVideoFormat;

class WebM extends DefaultVideoFormat
{

    protected $audioCodec = 'libvorbis';
    protected $videoCodec = 'libvpx';

    public function getExtraParams()
    {
        return '-f webm';
    }

    protected function getAvailableAudioCodecs()
    {
        return array('libvorbis');
    }

    protected function getAvailableVideoCodecs()
    {
        return array('libvpx');
    }

}