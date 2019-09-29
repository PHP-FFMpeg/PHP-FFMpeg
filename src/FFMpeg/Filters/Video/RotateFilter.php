<?php

declare(strict_types=1);

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
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class RotateFilter implements VideoFilterInterface
{
    use TPriorityFilter;

    const ROTATE_90 = 'transpose=1';
    const ROTATE_180 = 'hflip,vflip';
    const ROTATE_270 = 'transpose=2';

    /** @var string */
    private $angle;
    /** @var int */
    private $priority;

    public function __construct($angle, int $priority = 0)
    {
        $this->setAngle($angle);
        $this->priority = $priority;
    }

    /**
     * @return Dimension
     */
    public function getAngle(): Dimension
    {
        return $this->angle;
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format)
    {
        // swap width and height if needed by rotation factor
        if (self::ROTATE_90 === $this->angle || self::ROTATE_270 === $this->angle) {
            foreach ($video->getStreams()->videos() as $stream) {
                if ($stream->has('width') && $stream->has('height')) {
                    $width = $stream->get('width');
                    $stream->set('width', $stream->get('height'));
                    $stream->set('height', $width);
                }
            }
        }

        return ['-vf', $this->angle, '-metadata:s:v:0', 'rotate=0'];
    }

    private function setAngle($angle)
    {
        switch ($angle) {
            case self::ROTATE_90:
            case self::ROTATE_180:
            case self::ROTATE_270:
                $this->angle = $angle;
                break;
            default:
                throw new InvalidArgumentException('Invalid angle value.');
        }
    }
}
