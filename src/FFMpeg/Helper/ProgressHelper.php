<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Helper;

use FFMpeg\FFProbe;

/**
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
abstract class ProgressHelper implements HelperInterface
{
    /**
     * @var number
     */
    protected $duration = null;

    /**
     * transcoding rate in kb/s
     *
     * @var number
     */
    protected $rate;

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
     * Percentage of transcoding progress (0 - 100)
     *
     * @var number
     */
    protected $percent = 0;

    /**
     * Time remaining (seconds)
     *
     * @var number
     */
    protected $remaining = null;

    /**
     * @var FFProbe
     */
    protected $prober;

    /**
     * @var Closure|string|array
     */
    protected $callback;

    /**
     * @param mixed $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Used to ease testing.
     *
     * @param number $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /*
     *  {@inheritdoc}
     */
    public function transcodeCallback($channel, $content)
    {
        $progress = $this->parseProgress($content);

        if (is_array($progress)) {
            call_user_func_array($this->callback, $progress);
        }
    }

    /*
     *  {@inheritdoc}
     */
    public function setProber(FFProbe $prober)
    {
        $this->prober = $prober;
    }

    /*
     *  {@inheritdoc}
     */
    public function open($pathfile)
    {
        if ($this->prober === null) {
            throw new \RuntimeException('Unable to report audio progress without a prober');
        }

        $format = json_decode($this->prober->probeFormat($pathfile), true);

        if ($format === null || count($format) === 0 || isset($format['size']) === false) {
            throw new \RuntimeException('Unable to probe format for ' . $pathfile);
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
            return null;
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
            $this->rate = floor($rate);
        }

        $this->percent = floor($percent * 100);
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
        if (!empty($ar[1])) {
            $duration += intval($ar[1]) * 60;
        }
        if (!empty($ar[2])) {
            $duration += intval($ar[2]) * 60 * 60;
        }

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
