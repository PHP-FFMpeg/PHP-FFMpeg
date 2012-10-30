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
 * The base audio interface
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface AudioInterface
{

    /**
     * Get the kiloBitrate value
     *
     * @return integer
     */
    public function getKiloBitrate();

    /**
     * Return an array of extra parameters to add to ffmpeg commandline
     *
     * @return array()
     */
    public function getExtraParams();

}
