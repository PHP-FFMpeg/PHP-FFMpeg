<?php
declare (strict_types = 1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe;

use FFMpeg\FFProbe;
use FFMpeg\Exception\InvalidArgumentException;

/**
 * Responsible for parsing output of `ffprobe`
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2018
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  FFProbe
 */
class OutputParser implements OutputParserInterface
{

    /**
     * Keys of values that are considered numeric when parsing streams of a file.
     */
    protected const NUMERIC_VALUES = [
        'index',
        'width',
        'height',
        'channels',
        'bits_per_sample',
        'has_b_frames',
        'level',
        'start_pts',
        'duration_ts'
    ];


    /**
     * @inheritDoc
     */
    public function parse(string $type, string $data) : array
    {
        switch ($type) {
            case FFProbe::TYPE_FORMAT:
                return $this->parseFormat($data);
                break;
            case FFProbe::TYPE_STREAMS:
                return $this->parseStreams($data);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown data type %s', $type));
        }
    }

    private function parseFormat(string $data) : array
    {
        $ret = [];

        foreach (explode(PHP_EOL, $data) as $line) {
            if (in_array($line, ['[FORMAT]', '[/FORMAT]'])) {
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if ('nb_streams' === $key) {
                $value = (int)$value;
            }

            if (0 === strpos($key, 'TAG:')) {
                if (!isset($ret['tags'])) {
                    $ret['tags'] = [];
                }
                $ret['tags'][substr($key, 4)] = $value;
            } else {
                $ret[$key] = $value;
            }
        }

        return ['format' => $ret];
    }

    private function parseStreams(string $data) : array
    {
        $ret = [];
        $currentStreamNumber = -1;

        foreach (explode(PHP_EOL, $data) as $line) {
            if ($line === '[/STREAM]') {
                continue;
            }

            if ($line === '[STREAM]') {
                // detect start of a new stream
                $ret[++$currentStreamNumber] = [];
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if ('N/A' === $value) {
                continue;
            }
            if ('profile' === $key && 'unknown' === $value) {
                continue;
            }

            if (in_array($key, self::NUMERIC_VALUES)) {
                $value = (int)$value;
            }

            if (0 === strpos($key, 'TAG:')) {
                if (!isset($ret[$currentStreamNumber]['tags'])) {
                    $ret[$currentStreamNumber]['tags'] = [];
                }
                $ret[$currentStreamNumber]['tags'][substr($key, 4)] = $value;
            } elseif (0 === strpos($key, 'DISPOSITION:')) {
                if (!isset($ret[$currentStreamNumber]['disposition'])) {
                    $ret[$currentStreamNumber]['disposition'] = [];
                }
                $ret[$currentStreamNumber]['disposition'][substr($key, 12)] = $value;
            } else {
                $ret[$currentStreamNumber][$key] = $value;
            }
        }

        return ['streams' => $ret];
    }
}
