<?php

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
use FFMpeg\FFProbe\DataMapping\{
    Format,
    MapableData,
    StreamCollection,
    Stream
};
use FFMpeg\Exception\InvalidArgumentException;

class Mapper implements MapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(string $type, $data): MapableData
    {
        switch ($type) {
            case FFProbe::TYPE_FORMAT:
                return $this->mapFormat($data);
            case FFProbe::TYPE_STREAMS:
                return $this->mapStreams($data);
            default:
                throw new InvalidArgumentException(sprintf(
                    'Invalid type `%s`.',
                    $type
                ));
        }
    }

    private function mapFormat($data): Format
    {
        return new Format($data['format']);
    }

    private function mapStreams($data): StreamCollection
    {
        $streams = new StreamCollection();

        foreach ($data['streams'] as $properties) {
            $streams->add(new Stream($properties));
        }

        return $streams;
    }
}
