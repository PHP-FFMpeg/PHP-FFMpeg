<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Coordinate;

use FFMpeg\Exception\InvalidArgumentException;

class TimeCode
{
    /**
     * @var float
     */
    private $hours;

    /**
     * @var float
     */
    private $minutes;

    /**
     * @var float
     */
    private $seconds;

    /**
     * @var float
     */
    private $frames;

    /**
     * Creates a new timecode based on absolutate numbers.
     *
     * @param float $hours   Absolute number of hours
     * @param float $minutes Absolute number of minutes
     * @param float $seconds Absolute number of seconds
     * @param float $frames  Absolute number of frames
     */
    public function __construct(float $hours, float $minutes, float $seconds, float $frames)
    {
        $this->hours = $hours;
        $this->minutes = $minutes;
        $this->seconds = $seconds;
        $this->frames = $frames;
    }

    /**
     * Prints out the TimeCode in the format
     * `HH:MM:SS.FF`
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%02d:%02d:%02d.%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    /**
     * Creates timecode from string.
     *
     * @param  string $timecode
     * @return TimeCode
     * @throws InvalidArgumentException In case an invalid timecode is supplied
     */
    public static function fromString($timecode): TimeCode
    {
        $days = 0;

        if (preg_match('/^[0-9]+:[0-9]+:[0-9]+:[0-9]+\.[0-9]+$/', $timecode)) {
            list($days, $hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d:%d.%d');
        } elseif (preg_match('/^[0-9]+:[0-9]+:[0-9]+:[0-9]+:[0-9]+$/', $timecode)) {
            list($days, $hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d:%d:%d');
        } elseif (preg_match('/^[0-9]+:[0-9]+:[0-9]+\.[0-9]+$/', $timecode)) {
            list($hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d.%s');
        } elseif (preg_match('/^[0-9]+:[0-9]+:[0-9]+:[0-9]+$/', $timecode)) {
            list($hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d:%s');
        } else {
            throw new InvalidArgumentException(sprintf('Unable to parse timecode %s', $timecode));
        }

        $hours += $days * 24;

        return new static($hours, $minutes, $seconds, $frames);
    }

    /**
     * Creates timecode from number of seconds.
     *
     * @param  float $quantity
     * @return TimeCode
     */
    public static function fromSeconds(float $quantity): TimeCode
    {
        $minutes = $hours = $frames = 0;

        $frames = round(100 * ($quantity - floor($quantity)));
        $seconds = floor($quantity);

        if ($seconds > 59) {
            $minutes = floor($seconds / 60);
            $seconds = $seconds % 60;
        }
        if ($minutes > 59) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
        }

        return new static($hours, $minutes, $seconds, $frames);
    }

    /**
     * Returns this timecode in seconds
     *
     * @return int
     */
    public function toSeconds(): int
    {
        $seconds = 0;

        $seconds += $this->hours * 60 * 60;
        $seconds += $this->minutes * 60;
        $seconds += $this->seconds;

        // TODO: Handle frames?

        return (int) $seconds;
    }

    /**
     * Helper function wether `$timecode` is after this one
     *
     * @internal
     * @param    TimeCode $timecode The Timecode to compare
     * @return   bool
     */
    public function isAfter(TimeCode $timecode): bool
    {
        // convert everything to seconds and compare
        return ($this->toSeconds() > $timecode->toSeconds());
    }
}
