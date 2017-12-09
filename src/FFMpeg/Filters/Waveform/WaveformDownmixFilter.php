<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Waveform;

use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Waveform;

class WaveformDownmixFilter implements WaveformFilterInterface
{

    /** @var boolean */
    private $downmix;
    /** @var integer */
    private $priority;

    // By default, the downmix value is set to FALSE.
    public function __construct($downmix = FALSE, $priority = 0)
    {
        $this->downmix = $downmix;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getDownmix()
    {
        return $this->downmix;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Waveform $waveform)
    {
        $commands = array();

        foreach ($waveform->getAudio()->getStreams() as $stream) {
            if ($stream->isAudio()) {
                try {
                    
                    // If the downmix parameter is set to TRUE, we add an option to the FFMPEG command
                    if($this->downmix == TRUE) {
                        $commands[] = '"aformat=channel_layouts=mono"';
                    }
                    
                    break;

                } catch (RuntimeException $e) {

                }
            }
        }

        return $commands;
    }
}
