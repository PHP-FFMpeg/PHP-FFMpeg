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
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Media\Video;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;

class RotateFilter implements VideoFilterInterface
{

    use TPriorityFilter;

    /**
     * Rotates the video by 90°
     */
    const ROTATE_90 = 'transpose=1';

    /**
     * Rotates the video by 180°
     * (vertical and horizontal)
     */
    const ROTATE_180 = 'hflip,vflip';

    /**
     * Rotates the video by 270°
     */
    const ROTATE_270 = 'transpose=2';

    /**
     * @var string
     */
    private $angle;

    /**
     * @var int
     */
    private $priority;

    public function __construct(string $angle, int $priority = 0)
    {
        $this->setAngle($angle);
        $this->setPriority($priority);
    }

    /**
     * @return string
     */
    public function getAngle(): string
    {
        return $this->angle;
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format): array
    {
        if (in_array($this->angle, [self::ROTATE_90, self::ROTATE_270])) {
            foreach ($video->getStreams()->getVideoStreams() as $stream) {
                if ($stream->has('width') && $stream->has('height')) {
                    // swap height & width
                    $width = $stream->get('width');
                    $stream->set('width', $stream->get('height'));
                    $stream->set('height', $width);
                }
            }
        }

        return ['-vf', $this->getAngle(), '-metadata:s:v:0', 'rotate=0'];
    }

    private function setAngle(string $angle): void
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
