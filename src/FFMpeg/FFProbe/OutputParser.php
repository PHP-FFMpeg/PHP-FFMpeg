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

namespace FFMpeg\FFProbe;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\FFProbe;

class OutputParser implements OutputParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $type, string $data)
    {
        switch ($type) {
            case FFProbe::TYPE_FORMAT:
                return $this->parseFormat($data);
                break;
            case FFProbe::TYPE_STREAMS:
                return $this->parseStreams($data);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown data type %s to parse output.', $type));
        }
    }

    private function parseFormat(string $data)
    {
        $ret = [];

        foreach (explode(PHP_EOL, $data) as $line) {

            if ('[FORMAT]' === $line || '[/FORMAT]' === $line) {
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if ('nb_streams' === $key) {
                $value = (int) $value;
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

    private function parseStreams(string $data)
    {
        $ret = [];
        $n = -1;

        foreach (explode(PHP_EOL, $data) as $line) {
            if ($line == '[STREAM]') {
                $n++;
                $ret[$n] = [];
                continue;
            }
            if ($line == '[/STREAM]') {
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

            if (in_array($key, ['index', 'width', 'height', 'channels', 'bits_per_sample', 'has_b_frames', 'level', 'start_pts', 'duration_ts'])) {
                $value = (int) $value;
            }

            if (0 === strpos($key, 'TAG:')) {
                if (!isset($ret[$n]['tags'])) {
                    $ret[$n]['tags'] = [];
                }

                $ret[$n]['tags'][substr($key, 4)] = $value;
            } elseif (0 === strpos($key, 'DISPOSITION:')) {
                if (!isset($ret[$n]['disposition'])) {
                    $ret[$n]['disposition'] = [];
                }

                $ret[$n]['disposition'][substr($key, 12)] = $value;
            } else {
                $ret[$n][$key] = $value;
            }
        }

        return ['streams' => $ret];
    }
}
