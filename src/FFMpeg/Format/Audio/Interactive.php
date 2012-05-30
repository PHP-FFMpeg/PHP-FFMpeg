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
 * The interactive audio interface. This provide a method to list available
 * codecs. This is usefull to build interactive development and switch between
 * different codecs
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface Interactive extends Transcodable
{

    /**
     * Returns the list of available audio codecs for this format
     *
     * @return array
     */
    public function getAvailableAudioCodecs();
}
