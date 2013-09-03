<?php
namespace FFMpeg\Filters\Video;

use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

/**
 * Add custom Pixel Format
 */
class FaststartFilter implements VideoFilterInterface
{
    private $priority;

    public function __construct( $priority = 12)
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
        return array('-movflags', 'faststart');
    }
}
