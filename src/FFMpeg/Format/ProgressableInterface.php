<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format;

use Evenement\EventEmitterInterface;
use Alchemy\BinaryDriver\Listeners\ListenerInterface;
use FFMpeg\FFProbe;
use FFMpeg\Media\MediaTypeInterface;

interface ProgressableInterface extends EventEmitterInterface
{

    /**
     * Creates the progress listener.
     *
     * @param MediaTypeInterface    $media
     * @param FFProbe               $ffprobe
     * @param int                   $passes    The current pas snumber
     * @param int                   $total   The total pass number
     * @param int                   $duration   The new video duration
     *
     * @return ListenerInterface[] An array of listeners
     */
    public function createProgressListener(
        MediaTypeInterface $media,
        FFProbe $ffprobe,
        int $passes,
        int $totalPasses,
        int $duration = 0
    ) : array;
}
