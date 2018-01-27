<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe;

interface OptionsTesterInterface
{

    /**
     * Tells if the given option is supported by ffprobe.
     *
     * @param  string $optionName The option to test
     * @return bool
     */
    public function has(string $optionName): bool;
}
