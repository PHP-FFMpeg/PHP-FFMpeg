<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\ProgressListener;

use Alchemy\BinaryDriver\Listeners\ListenerInterface;
use Evenement\EventEmitter;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\TimeCode;

/**
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
abstract class AbstractProgressListener extends EventEmitter implements ListenerInterface
{
    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $totalSize;

    /**
     * @var int
     */
    private $currentSize;

    /**
     * @var int
     */
    private $currentTime;

    /**
     * @var float
     */
    private $lastOutput = null;

    /**
     * @var FFProbe
     */
    private $ffprobe;

    /**
     * @var string
     */
    private $pathfile;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var int
     */
    private $currentPass;

    /**
     * @var int
     */
    private $totalPass;

    /**
     * Transcoding rate in kb/s
     *
     * @var int
     */
    private $rate;

    /**
     * Percentage of transcoding progress (0 - 100)
     *
     * @var int
     */
    private $percent = 0;

    /**
     * Time remaining (seconds)
     *
     * @var int
     */
    private $remaining = null;

    /**
     * @param   FFProbe $ffprobe
     * @param   string  $pathfile
     * @param   integer $currentPass The cureent pass number
     * @param   integer $totalPasses The total number of passes
     * @param   integer $duration    The new (overwritten) duration (needed when clipping the media file)
     * @throws RuntimeException
     */
    public function __construct(FFProbe $ffprobe, string $pathfile, int $currentPass, int $totalPass, int $duration = 0)
    {
        $this->ffprobe = $ffprobe;
        $this->pathfile = $pathfile;
        $this->currentPass = $currentPass;
        $this->totalPass = $totalPass;
        $this->duration = $duration;
    }

    /**
     * @return FFProbe
     */
    public function getFFProbe() : FFProbe
    {
        return $this->ffprobe;
    }

    /**
     * @return string
     */
    public function getPathfile() : string
    {
        return $this->pathfile;
    }

    /**
     * @return int
     */
    public function getCurrentPass() : int
    {
        return $this->currentPass;
    }

    /**
     * @return int
     */
    public function getTotalPass() : int
    {
        return $this->totalPass;
    }

    /**
     * @return int
     */
    public function getCurrentTime() : int
    {
        return $this->currentTime;
    }

    /**
     * @inheritDoc
     */
    public function handle($type, $data)
    {
        if (null !== $progress = $this->parseProgress($data)) {
            $this->emit('progress', array_values($progress));
        }
    }

    /**
     * @inheritDoc
     */
    public function forwardedEvents() : array
    {
        return [];
    }

    /**
     * Get the regex pattern to match a ffmpeg stderr status line
     *
     * @return string
     */
    abstract protected function getPattern() : string;

    /**
     * @param string $progress A ffmpeg stderr progress output
     *
     * @return array|null the progressinfo array or null if there's no progress available yet.
     */
    private function parseProgress($progress) : ? array
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        if (null === $this->totalSize || null === $this->duration) {
            return null;
        }

        $matches = [];

        if (preg_match($this->getPattern(), $progress, $matches) !== 1) {
            return null;
        }

        $currentDuration = $this->convertDuration($matches[2]);
        $currentTime = microtime(true);
        $currentSize = trim(str_replace('kb', '', strtolower($matches[1])));
        $percent = max(0, min(1, $currentDuration / $this->duration));

        if ($this->lastOutput !== null) {
            $delta = $currentTime - $this->lastOutput;

            // Check the type of the currentSize variable and convert it to an integer if needed.
            if (!is_numeric($currentSize)) {
                $currentSize = (int)$currentSize;
            }

            $deltaSize = $currentSize - $this->currentSize;
            $rate = $deltaSize * $delta;
            if ($rate > 0) {
                $totalDuration = $this->totalSize / $rate;
                $this->remaining = floor($totalDuration - ($totalDuration * $percent));
                $this->rate = floor($rate);
            } else {
                $this->remaining = 0;
                $this->rate = 0;
            }
        }

        $percent = $percent / $this->totalPass + ($this->currentPass - 1) / $this->totalPass;

        $this->percent = floor($percent * 100);
        $this->lastOutput = $currentTime;
        $this->currentSize = (int)$currentSize;
        $this->currentTime = $currentDuration;

        return $this->getProgressInfo();
    }

    /**
     * @param string $rawDuration in the format 00:00:00.00
     * @return int
     */
    private function convertDuration(string $rawDuration) : int
    {
        return TimeCode::fromString($rawDuration)->toSeconds();
    }

    /**
     * @return array|null
     */
    private function getProgressInfo() : ? array
    {
        if ($this->remaining === null) {
            return null;
        }

        return [
            'percent' => $this->percent,
            'remaining' => $this->remaining,
            'rate' => $this->rate
        ];
    }

    private function initialize() : void
    {
        try {
            $format = $this->ffprobe->format($this->pathfile);
        } catch (RuntimeException $e) {
            return;
        }

        if ($format->has('size') === false || $format->has('duration') === false) {
            return;
        }

        $this->duration = (int)$this->duration > 0 ? $this->duration : $format->get('duration');
        $this->totalSize = $format->get('size') / 1024 * ($this->duration / $format->get('duration'));
        $this->initialized = true;
    }
}
