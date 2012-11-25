<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Progress;

/**
 *
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
abstract class Progress
{
    /**
     * @var array
     */
    protected $format;

    /**
     * @var number
     */
    protected $totalSize;

    /**
     * @var number
     */
    protected $currentSize;

    /**
     * @var number
     */
    protected $currentTime;

    /**
     * @var double
     */
    protected $lastOutput = null;

    /**
     * @var number
     */
    protected $percent = 0;

    /**
     * @var number
     */
    protected $remaining = null;

    /**
     * @param array $format
     */
    public function __construct(array $format)
    {
        if ($format === null || count($format) === 0 || isset($format['size']) === false) {
            throw new \InvalidargumentException('You need to pass a valid format array to the '  . __CLASS__ . '. Use FFMpeg\FFProbe to retrieve it.');
        }

        $this->format = $format;
        $this->totalSize = $format['size'] / 1024;
        $this->duration = $format['duration'];
    }

    /**
     * @param string $progress A ffmpeg stderr progress output
     * @return array the progressinfo array or null if there's no progress available yet.
     */
    public function parseProgress($progress)
    {
        $matches = array();

        if (preg_match($this->getPattern(), $progress, $matches) !== 1) {
            return;
        }

        $currentDuration = $this->convertDuration($matches[2]);
        $currentTime = $this->microtimeFloat();
        $currentSize = trim(str_replace('kb', '', strtolower(($matches[1]))));
        $percent = $currentDuration/ $this->duration;

        if ($this->lastOutput !== null) {
            $delta = $currentTime - $this->lastOutput;
            $deltaSize = $currentSize - $this->currentSize;
            $rate = $deltaSize * $delta;
            $totalDuration = $this->totalSize / $rate;
            $this->remaining = floor($totalDuration - ($totalDuration * $percent));
            $this->rate = floor($rate) . ' kb/s';
        }

        $this->percent = floor($percent * 100) . '%';
        $this->lastOutput = $currentTime;
        $this->currentSize = (int) $currentSize;
        $this->currentTime = $currentDuration;

        return $this->getProgressInfo();
    }

    /**
     *
     * @param string $rawDuration in the format 00:00:00.00
     * @return number
     */
    protected function convertDuration($rawDuration)
    {
        $ar = array_reverse(explode(":", $rawDuration));
        $duration = floatval($ar[0]);
        if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
        if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

        return $duration;
    }

    /**
     * @return array
     */
    public function getProgressInfo()
    {
        if ($this->remaining === null) {
            return null;
        }

        return array(
            'currentSize' => $this->currentSize,
            'currentTime' => $this->currentTime,
            'percent' => $this->percent,
            'remaining' => $this->remaining,
            'rate' => $this->rate
        );
    }

    /**
     * @return number
     */
    protected function microtimeFloat()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * Get the regex pattern to match a ffmpeg stderr status line
     */
    abstract function getPattern();

}
