<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Options\Video;

use FFMpeg\Filters\FilterGraph;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

/**
 * Add a series of filters
 *
 * @see https://www.ffmpeg.org/ffmpeg-filters.html
 */
class FilterOption implements VideoOptionInterface
{
    private $priority;
    private $graph;

    public function __construct(FilterGraph $graph, $priority = 0)
    {
        $this->graph = $graph;
    }

    /**
     * Get the graph of applied filters
     *
     * @return FilterGraph
     */
    public function getGraph()
    {
        return $this->getGraph();
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
        return array('-filter:v', (string) $this->graph);
    }
}
