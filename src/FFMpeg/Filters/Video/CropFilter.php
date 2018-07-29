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

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\Point;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class CropFilter implements VideoFilterInterface
{

    use TPriorityFilter;

    /**
     * @var integer
     */
    protected $priority;

    /**
     * @var Dimension
     */
    protected $dimension;

    /**
     * @var Point
     */
    protected $point;

    public function __construct(Point $point, Dimension $dimension, int $priority = 0)
    {
        $this->dimension = $dimension;
        $this->point = $point;
        $this->setPriority($priority);
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format) : array
    {
        foreach ($video->getStreams()->getVideoStreams() as $stream) {
            if ($stream->has('width') && $stream->has('height')) {
                $stream->set('width', $this->dimension->getWidth());
                $stream->set('height', $this->dimension->getHeight());
            }
        }

        $width = $this->dimension->getWidth();
        $height = $this->dimension->getHeight();

        $x = $this->point->getX();
        $y = $this->point->getY();

        return [
            '-filter:v',
            'crop=' . $width . ':' . $height . ':' . $x . ':' . $y
        ];
    }
}
