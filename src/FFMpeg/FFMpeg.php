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
use FFMpeg\Format\AudioFormat;
use FFMpeg\Format\VideoFormat;
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
     * Opens a file in order to be processed
     *
     * @param  string                                 $pathfile A pathfile
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
     *
     * @param  integer                    $time   The time in second where to take the snapshot
     * @param  string                     $output The pathfile where to write
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
     * @param  AudioFormat         $format         The output format
     * @param  string                     $outputPathfile The pathfile where to write
     * @param  integer                    $threads        The number of threads to use
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     * @throws LogicException
     */
    public function encode(AudioFormat $format, $outputPathfile, $threads = 1)
    {
        if ( ! $this->pathfile) {
            throw new LogicException('No file open');
        }

        $threads = max(min($threads, 64), 1);

        switch (true) {
            case $format instanceof VideoFormat:
                $this->encodeVideo($format, $outputPathfile, $threads);
                break;
            default:
            case $format instanceof AudioFormat:
                $this->encodeAudio($format, $outputPathfile, $threads);
                break;
        }

        return $this;
    }

    /**
     * Encode to audio
     *
     * @param  AudioFormat         $format         The output format
     * @param  string                     $outputPathfile The pathfile where to write
     * @param  integer                    $threads        The number of threads to use
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     */
    protected function encodeAudio(AudioFormat $format, $outputPathfile, $threads)
    {
        $cmd = $this->binary
            . ' -y -i '
            . escapeshellarg($this->pathfile)
            . ' ' . $format->getExtraParams()
            . ' -threads ' . $threads
            . ' -acodec ' . $format->getAudioCodec()
            . ' -ab ' . $format->getKiloBitrate() . 'k '
            . ' -ac 2 -ar ' . $format->getAudioSampleRate()
            . ' ' . escapeshellarg($outputPathfile);

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
     * @param  VideoFormat         $format         The output format
     * @param  string                     $outputPathfile The pathfile where to write
     * @param  integer                    $threads        The number of threads to use
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     */
    protected function encodeVideo(VideoFormat $format, $outputPathfile, $threads)
    {
        $cmd_part1 = $this->binary
            . ' -y -i '
            . escapeshellarg($this->pathfile) . ' '
            . $format->getExtraParams() . ' ';

        $cmd_part2 = ' -s ' . $format->getWidth() . 'x' . $format->getHeight()
            . ' -r ' . $format->getFrameRate()
            . ' -vcodec ' . $format->getVideoCodec()
            . ' -b ' . $format->getKiloBitrate() . 'k -g 25 -bf 3'
            . ' -threads ' . $threads
            . ' -refs 6 -b_strategy 1 -coder 1 -qmin 10 -qmax 51 '
            . ' -sc_threshold 40 -flags +loop -cmp +chroma'
            . ' -me_range 16 -subq 7 -i_qfactor 0.71 -qcomp 0.6 -qdiff 4 '
            . ' -trellis 1 -qscale 1 '
            . '-acodec ' . $format->getAudioCodec() . ' -ab 92k ';

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
     * {@inheritdoc}
     *
     * @return string
     */
    protected static function getBinaryName()
    {
        return array('avconv', 'ffmpeg');
    }
}
