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

use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

/**
 * Synchronizes audio and video in case of desynchronized movies.
 */
class SynchronizeFilter implements VideoFilterInterface
{
    private $priority;

    public function __construct($priority = 12)
    {
        $this->priority = $priority;
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
    public function apply(Video $video, VideoInterface $format)
    {
        $streams = $video->getStreams();

        if (null === $videoStream = $streams->videos()->first()) {
            return array();
        }
        if (!$videoStream->has('start_time')) {
            return array();
        }

        $params = array(
            '-itsoffset',
            $videoStream->get('start_time'),
            '-i',
            $video->getPathfile(),
        );

        foreach ($streams as $stream) {
            if ($videoStream === $stream) {
                $params[] = '-map';
                $params[] = '1:' . $stream->get('index');
            } else {
                $params[] = '-map';
                $params[] = '0:' . $stream->get('index');
            }
        }

        return $params;
    }
}
