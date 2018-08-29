<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

/**
 * Resamples the audio
 *
 * @author     jens1o
 * @copyright  Jens Hausdorf 2018
 * @license    MIT License
 * @package    FFMpeg\Filters
 * @subpackage Audio
 */
class AudioResamplableFilter implements AudioFilterInterface
{

    use TPriorityFilter;

    /**
     * @var string
     */
    private $rate;

    /**
     * @var int
     */
    private $priority;

    public function __construct(int $rate, int $priority = 0)
    {
        $this->rate = $rate;
        $this->setPriority($priority);
    }

    /**
     * @return int
     */
    public function getRate() : int
    {
        return $this->rate;
    }

    /**
     * @inheritDoc
     */
    public function apply(Audio $audio, AudioInterface $format) : array
    {
        return ['-ac', '2', '-ar', (string)$this->rate];
    }
}
