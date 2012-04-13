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

/**
 * The Ogg video format
 *
 * @author Romain Neutron imprec@gmail.com
 */
class Ogg extends DefaultVideoFormat
{

    protected $audioCodec = 'libvorbis';
    protected $videoCodec = 'libtheora';

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('libvorbis');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs()
    {
        return array('libtheora');
    }

}