<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\LogicException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Format\Audio;
use FFMpeg\Format\Video;
use Symfony\Component\Process\Process;

/**
 * FFMpeg driver
 *
 * @author Romain Neutron imprec@gmail.com
 */
class FFMpeg extends Binary
{
    protected $pathfile;

    /**
     *
     * @var FFProbe
     */
    protected $prober;

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->prober = null;
        parent::__destruct();
    }

    /**
     * Opens a file in order to be processed
     *
     * @param  string                   $pathfile A pathfile
     * @return \FFMpeg\FFMpeg
     * @throws InvalidArgumentException
     */
    public function open($pathfile)
    {
        if ( ! file_exists($pathfile)) {
            $this->logger->addError(sprintf('FFmpeg failed to open %s', $pathfile));

            throw new InvalidArgumentException(sprintf('File %s does not exists', $pathfile));
        }

        $this->logger->addInfo(sprintf('FFmpeg opens %s', $pathfile));

        $this->pathfile = $pathfile;

        return $this;
    }

    /**
     * Set a prober
     *
     * @return \FFMpeg\FFMpeg
     */
    public function setProber(FFProbe $prober)
    {
        $this->prober = $prober;

        return $this;
    }

    /**
     * Close a file
     *
     * @return \FFMpeg\FFMpeg
     */
    public function close()
    {
        $this->logger->addInfo(sprintf('FFmpeg closes %s', $this->pathfile));

        $this->pathfile = null;

        return $this;
    }

    /**
     * Extract an image from a media file
     *
     * @param  integer          $time   The time in second where to take the snapshot
     * @param  string           $output The pathfile where to write
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     * @throws LogicException
     */
    public function extractImage($time, $output)
    {
        if ( ! $this->pathfile) {
            throw new LogicException('No file open');
        }

        $cmd = $this->binary
            . ' -i ' . escapeshellarg($this->pathfile)
            . ' -vframes 1 -ss ' . $time
            . ' -f image2 ' . escapeshellarg($output);

        $this->logger->addInfo(sprintf('FFmpeg executes command %s', $cmd));

        $process = new Process($cmd);

        try {
            $process->run();
        } catch (\RuntimeException $e) {

        }

        if ( ! $process->isSuccessful()) {
            $this->logger->addError(sprintf('FFmpeg command failed : %s', $process->getErrorOutput()));

            $this->cleanupTemporaryFile($output);

            throw new RuntimeException('Failed to extract image');
        }

        $this->logger->addInfo(sprintf('FFmpeg command successful'));

        return $this;
    }

    /**
     * Encode the file to the specified format
     *
     * @param  Audio            $format         The output format
     * @param  string           $outputPathfile The pathfile where to write
     * @param  integer          $threads        The number of threads to use
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     * @throws LogicException
     */
    public function encode(Audio $format, $outputPathfile, $threads = 1)
    {
        if ( ! $this->pathfile) {
            throw new LogicException('No file open');
        }

        $threads = max(min($threads, 64), 1);

        switch (true) {
            case $format instanceof Video:
                $this->encodeVideo($format, $outputPathfile, $threads);
                break;
            default:
            case $format instanceof Audio:
                $this->encodeAudio($format, $outputPathfile, $threads);
                break;
        }

        return $this;
    }

    /**
     * Encode to audio
     *
     * @param  Audio            $format         The output format
     * @param  string           $outputPathfile The pathfile where to write
     * @param  integer          $threads        The number of threads to use
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     */
    protected function encodeAudio(Audio $format, $outputPathfile, $threads)
    {
        $cmd = $this->binary
            . ' -y -i '
            . escapeshellarg($this->pathfile)
            . ' ' . $format->getExtraParams()
            . ' -threads ' . $threads
            . ' -ab ' . $format->getKiloBitrate() . 'k '
            . ' ' . escapeshellarg($outputPathfile);

        if ($format instanceof Audio\Transcodable) {
            $cmd .= ' -acodec ' . $format->getAudioCodec();
        }

        if ($format instanceof Audio\Resamplable) {
            $cmd .= ' -ac 2 -ar ' . $format->getAudioSampleRate();
        }

        $process = new Process($cmd);

        $this->logger->addInfo(sprintf('FFmpeg executes command %s', $cmd));

        try {
            $process->run();
        } catch (\RuntimeException $e) {

        }

        if ( ! $process->isSuccessful()) {
            $this->logger->addInfo(sprintf('FFmpeg command failed'));
            throw new RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        $this->logger->addInfo(sprintf('FFmpeg command successful'));

        return $this;
    }

    /**
     * Encode to video
     *
     * @param  Video            $format         The output format
     * @param  string           $outputPathfile The pathfile where to write
     * @param  integer          $threads        The number of threads to use
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     */
    protected function encodeVideo(Video $format, $outputPathfile, $threads)
    {
        $cmd_part1 = $this->binary
            . ' -y -i '
            . escapeshellarg($this->pathfile) . ' '
            . $format->getExtraParams() . ' ';

        $cmd_part2 = '';

        if ($format instanceof Video\Resizable) {
            if ( ! $this->prober) {
                throw new LogicException('You must set a valid prober if you use RESIZEMODE_INSET');
            }

            $result = json_decode($this->prober->probeStreams($this->pathfile), true);

            $originalWidth = $originalHeight = null;

            foreach ($result as $stream) {
                foreach ($stream as $name => $value) {
                    if ($name == 'width') {
                        $originalWidth = $value;
                        continue;
                    }
                    if ($name == 'value') {
                        $originalHeight = $value;
                        continue;
                    }
                }
            }

            if ($originalHeight !== null && $originalWidth !== null) {
                $dimensions = $format->getComputedDimensions($originalWidth, $originalHeight);

                $width = $this->getMultiple($dimensions->getWidth(), 16);
                $height = $this->getMultiple($dimensions->getHeight(), 16);

                $cmd_part2 .= ' -s ' . $width . 'x' . $height;
            }
        }

        if ($format instanceof Video\Resamplable) {
            $cmd_part2 .= ' -r ' . $format->getFrameRate();
        }

        if ($format instanceof Video\Transcodable) {
            $cmd_part2 .= ' -vcodec ' . $format->getVideoCodec();
        }

        $cmd_part2 .= ' -b ' . $format->getKiloBitrate() . 'k -g 25 -bf 3'
            . ' -threads ' . $threads
            . ' -refs 6 -b_strategy 1 -coder 1 -qmin 10 -qmax 51 '
            . ' -sc_threshold 40 -flags +loop -cmp +chroma'
            . ' -me_range 16 -subq 7 -i_qfactor 0.71 -qcomp 0.6 -qdiff 4 '
            . ' -trellis 1 -qscale 1 '
            . ' -ab 92k ';

        if ($format instanceof Audio\Transcodable) {
            $cmd_part2 .= '-acodec ' . $format->getAudioCodec();
        }

        $tmpFile = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'temp') . '.' . pathinfo($outputPathfile, PATHINFO_EXTENSION));

        $passes = array();

        $passes[] = $cmd_part1 . ' -pass 1 ' . $cmd_part2
            . ' -an ' . escapeshellarg($tmpFile->getPathname());

        $passes[] = $cmd_part1 . ' -pass 2 ' . $cmd_part2
            . ' -ac 2 -ar 44100 ' . escapeshellarg($outputPathfile);

        $process = null;

        foreach ($passes as $pass) {

            $this->logger->addInfo(sprintf('FFmpeg executes command %s', $pass));

            $process = new Process($pass);

            try {
                $process->run();
            } catch (\RuntimeException $e) {
                break;
            }
        }

        $this->cleanupTemporaryFile($tmpFile->getPathname());
        $this->cleanupTemporaryFile(getcwd() . '/ffmpeg2pass-0.log');
        $this->cleanupTemporaryFile(getcwd() . '/av2pass-0.log');
        $this->cleanupTemporaryFile(getcwd() . '/ffmpeg2pass-0.log.mbtree');

        if ( ! $process->isSuccessful()) {
            $this->logger->addInfo(sprintf('FFmpeg command failed'));
            throw new RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        $this->logger->addInfo(sprintf('FFmpeg command successful'));

        return $this;
    }

    /**
     * Removes unnecessary file
     *
     * @param string $pathfile
     */
    protected function cleanupTemporaryFile($pathfile)
    {
        if (file_exists($pathfile) && is_writable($pathfile)) {
            unlink($pathfile);
        }
    }

    /**
     * Returns the nearest multiple for a value
     *
     * @param  integer $value
     * @param  integer $multiple
     * @return integer
     */
    protected function getMultiple($value, $multiple)
    {
        $modulo = $value % $multiple;

        $ret = (int) $multiple;

        $halfDistance = $multiple / 2;
        if ($modulo <= $halfDistance)
            $bound = 'bottom';
        else
            $bound = 'top';

        switch ($bound) {
            default:
            case 'top':
                $ret = $value + $multiple - $modulo;
                break;
            case 'bottom':
                $ret = $value - $modulo;
                break;
        }

        if ($ret < $multiple) {
            $ret = (int) $multiple;
        }

        return (int) $ret;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    protected static function getBinaryName()
    {
        return array('avconv', 'ffmpeg');
    }
}
