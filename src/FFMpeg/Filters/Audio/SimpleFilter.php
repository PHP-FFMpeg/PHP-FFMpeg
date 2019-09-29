<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Media\Audio;
use FFMpeg\Format\AudioInterface;

class SimpleFilter implements AudioFilterInterface
{
    use TPriorityFilter;

    /** @var string[] */
    private $params;
    /** @var int */
    private $priority;

    public function __construct(array $params, int $priority = 0)
    {
        $this->params = $params;
        $this->priority = $priority;
    }

    /**
     * @inheritDoc
     */
    public function apply(Audio $audio, AudioInterface $format): array
    {
        return $this->params;
    }
}
