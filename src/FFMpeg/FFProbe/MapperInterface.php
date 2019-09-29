<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe;

use FFMpeg\FFProbe\DataMapping\MapableData;

interface MapperInterface
{
    /**
     * Maps data given its type.
     *
     * @param string $type One of FFProbe::TYPE_* constant
     * @param mixed[][] $data The data
     *
     * @return MapableData
     *
     * @throws InvalidArgumentException In case the type is not supported
     */
    public function map(string $type, $data): MapableData;
}
