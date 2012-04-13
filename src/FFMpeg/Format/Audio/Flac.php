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

/**
 * The Flac audio format
 *
 * @author Romain Neutron imprec@gmail.com
 */
class Flac extends DefaultAudioFormat
{

    protected $audioCodec = 'flac';

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('flac');
    }

}