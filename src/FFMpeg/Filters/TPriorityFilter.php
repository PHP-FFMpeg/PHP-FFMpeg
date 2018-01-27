<?php
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
     * Inits the given priority
     *
     * @param  int $priority
     * @return void
     */
    public function setPriority(int $priority = 0): void
    {
        $this->priority = $priority;
    }

    /**
     * Returns the current priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
