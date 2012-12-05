<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Helper;

use FFMpeg\FFProbe;

/**
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
interface HelperInterface
{
    /**
     * The callback from the ffmpeg process.
     *
     * @param string $channel (stdio|stderr)
     * @param string $content the current line of the ffmpeg output
     */
    function transcodeCallback($channel, $content);

    /**
     * The helper has access to a prober instance if available.
     *
     * @param FFProbe $prober
     */
    function setProber(FFProbe $prober);

    /**
     * Called when the input file is opened.
     *
     * @param string $pathfile
     */
    function open($pathfile);
}
