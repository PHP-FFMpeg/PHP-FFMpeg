<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Filters\Audio\AudioFilters;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\Audio\AudioFilterInterface;
use FFMpeg\Filters\FilterInterface;
use FFMpeg\Format\ProgressableInterface;

class Audio extends AbstractStreamableMedia
{
    /**
     * {@inheritdoc}
     *
     * @return AudioFilters
     */
    public function filters()
    {
        return new AudioFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Audio
     */
    public function addFilter(FilterInterface $filter)
    {
        if (!$filter instanceof AudioFilterInterface) {
            throw new InvalidArgumentException('Audio only accepts AudioFilterInterface filters');
        }

        $this->filters->add($filter);

        return $this;
    }

    /**
     * Exports the audio in the desired format, applies registered filters.
     *
     * @param FormatInterface $format
     * @param string          $outputPathfile
     *
     * @return Audio
     *
     * @throws RuntimeException
     */
    public function save(FormatInterface $format, $outputPathfile)
    {
        $listeners = null;

        if ($format instanceof ProgressableInterface) {
            $listeners = $format->createProgressListener($this, $this->ffprobe, 1, 1);
        }

        $commands = array_merge(array('-y', '-i', $this->pathfile), $format->getExtraParams());

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this, $format));
        }

        if ($this->driver->getConfiguration()->has('ffmpeg.threads')) {
            $commands[] = '-threads';
            $commands[] = $this->driver->getConfiguration()->get('ffmpeg.threads');
        }

        if (null !== $format->getAudioCodec()) {
            $commands[] = '-acodec';
            $commands[] = $format->getAudioCodec();
        }

        $commands[] = '-b:a';
        $commands[] = $format->getAudioKiloBitrate() . 'k';
        $commands[] = $outputPathfile;

        try {
            $this->driver->command($commands, false, $listeners);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($outputPathfile);
            throw new RuntimeException('Encoding failed', $e->getCode(), $e);
        }

        return $this;
    }
}
