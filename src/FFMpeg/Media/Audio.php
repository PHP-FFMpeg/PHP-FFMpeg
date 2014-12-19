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
use FFMpeg\Format\FormatInterface;
use FFMpeg\Options\Audio\AudioOptionInterface;
use FFMpeg\Options\Audio\AudioOptions;
use FFMpeg\Options\Audio\SimpleOption;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Options\OptionInterface;

class Audio extends AbstractStreamableMedia
{
    /**
     * {@inheritdoc}
     *
     * @return AudioOptions
     */
    public function options()
    {
        return new AudioOptions($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Audio
     */
    public function addOption(OptionInterface $option)
    {
        if (!$option instanceof AudioOptionInterface) {
            throw new InvalidArgumentException('Audio only accepts AudioFilterInterface filters');
        }

        $this->options->add($option);

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

        $commands = array('-y', '-i', $this->pathfile);

        $options = clone $this->options;
        $options->add(new SimpleOption($format->getExtraParams(), 10));

        if ($this->driver->getConfiguration()->has('ffmpeg.threads')) {
            $options->add(new SimpleOption(array('-threads', $this->driver->getConfiguration()->get('ffmpeg.threads'))));
        }
        if (null !== $format->getAudioCodec()) {
            $options->add(new SimpleOption(array('-acodec', $format->getAudioCodec())));
        }

        foreach ($options as $option) {
            $commands = array_merge($commands, $option->apply($this, $format));
        }

        if (null !== $format->getAudioKiloBitrate()) {
            $commands[] = '-b:a';
            $commands[] = $format->getAudioKiloBitrate() . 'k';
        }
        if (null !== $format->getAudioChannels()) {
            $commands[] = '-ac';
            $commands[] = $format->getAudioChannels();
        }
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
