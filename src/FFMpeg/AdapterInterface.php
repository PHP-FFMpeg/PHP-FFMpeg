<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use Monolog\Logger;

/**
 * FFMpeg Adapter interface
 *
 * @author Romain Neutron imprec@gmail.com
 */
interface AdapterInterface
{

    /**
     * Loads the adapter
     */
    public static function load(Logger $logger);
}
