<?php
/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Dr Pshtiwan <drpshtiwan@thejano.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Add a filter to change the sample format of the audio, to see the available formats
 * run ffmpeg -sample_fmts
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

class AudioSampleFormatFilter implements AudioFilterInterface
{
    private string $format;

    private int $priority;

    public function __construct(string $format, $priority = 0)
    {
        $this->format = $format;
        $this->priority = $priority;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Audio $audio, AudioInterface $format)
    {
        return ['-sample_fmt', $this->format];
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
