<?php

declare(strict_types=1);

namespace FFMpeg;

use FFMpeg\Coordinate\TimeCode;

class TrimSegment
{

    public function __construct(
        private TimeCode $start,
        private TimeCode $duration,
        private string $input,
    ) {
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function buildPartOfCommand(): array
    {
        return ['-ss', (string) $this->start, '-t', (string) $this->duration, '-i', $this->input];
    }
}
