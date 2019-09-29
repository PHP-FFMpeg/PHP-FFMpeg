<?php

declare(strict_types=1);

namespace FFMpeg\Filters\Audio\Metadata;

class Metadata
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $artist;

    /** @var string */
    protected $composer;

    /** @var string */
    protected $album;

    /** @var string */
    protected $track;

    /** @var string */
    protected $artwork;

    /** @var string */
    protected $year;

    /** @var string */
    protected $genre;

    /** @var string */
    protected $description;

    public function __construct(
        ?string $title = null,
        ?string $artist = null,
        ?string $composer = null,
        ?string $album = null,
        ?string $track = null,
        ?string $artwork = null,
        ?string $year = null,
        ?string $genre = null,
        ?string $description = null
    ) {
        $this->title = $title;
        $this->artist = $artist;
        $this->composer = $composer;
        $this->album = $album;
        $this->track = $track;
        $this->artwork = $artwork;
        $this->year = $year;
        $this->genre = $genre;
        $this->description = $description;
    }

    // TODO: Implement this
    public function toFFMpegCommandPart(): array
    {
        throw new \RuntimeException("TODO: Implement");
        return [];
    }
}
