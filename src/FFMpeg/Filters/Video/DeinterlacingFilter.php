<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Filters\AbstractFilter;

class DeInterlacingFilter extends AbstractFilter implements VideoFilterInterface
{
    const MOD_SEND_FRAME = 0;
    const MOD_SEND_FIELD = 1;
    const MOD_SEND_FRAME_NO_SPATIAL = 2;
    const MOD_SEND_FIELD_NO_SPATIAL = 3;

    const PARITY_AUTO = -1;
    const PARITY_TTF = 0;
    const PARITY_BFF = 1;

    public function __construct($mod = self::MOD_SEND_FRAME, $parity = self::PARITY_AUTO)
    {
        parent::__construct('yadif', array($mod, $parity));
    }
}
