<?php

declare(strict_types=1);

namespace FFMpeg\Filters;

/**
 * Provides methods for setting and getting priorities in media filters
 *
 * @author     jens1o
 * @copyright  Jens Hausdorf 2018
 * @license    MIT License
 * @package    FFMpeg
 * @subpackage Filters
 */
trait TPriorityFilter
{
    /**
     * Sets the given priority for this filter. A higher priority(~ tinier number) means that
     * this filter will be more likely at the start point of the generated command
     * that ffmpeg/ffprobe will receive.
     *
     * Examples:
     * Filter A has priority of 9.
     * Filter B has priority of 3.
     *
     * In the final command, the subcommand of B would be placed before A.
     *
     * @param  int $priority
     * @return void
     */
    public function setPriority(int $priority = 0): void
    {
        $this->priority = $priority;
    }

    /**
     * Returns the current priority.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
