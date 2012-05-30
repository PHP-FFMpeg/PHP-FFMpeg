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
interface Audio
{

    /**
     * Get the kiloBitrate value
     *
     * @return integer
     */
    public function getKiloBitrate();
    
    /**
     * Give som extra parameters to add to ffmpeg commandline
     * Parameters MUST be escaped
     * 
     * @return string
     */
    public function getExtraParams();

}
