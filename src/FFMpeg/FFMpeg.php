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

use \Symfony\Component\Process\Process;

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
     * @param string $pathfile
     * @throws Exception\InvalidFileArgumentException
     */
    public function open($pathfile)
    {
        if ( ! file_exists($pathfile))
        {
            $this->logger->addError(sprintf('Request to open %s failed', $pathfile));

            throw new Exception\InvalidFileArgumentException(sprintf('File %s does not exists', $pathfile));
        }

        $this->logger->addInfo(sprintf('FFmpeg opens %s', $pathfile));

        $this->pathfile = $pathfile;
    }

    /**
     *
     * @param int $time         The time in second where to take the snapshot
     * @param string $output    The pathfile where to write
     * @param int $width        The width of the image
     * @param int $height       The height of the image
     * @return boolean          True if success
     * @throws Exception\RuntimeException
     */
    public function extractImage($time, $output, $width, $height)
    {
        if ( ! $this->pathfile)
        {
            throw new Exception\RuntimeException('No file open');
        }

        $cmd = $this->binary
          . ' -i ' . escapeshellarg($this->pathfile)
          . ' -vframes 1 -ss ' . $time
          . ' -f image2 ' . escapeshellarg($output);

        $this->logger->addInfo(sprintf('Executing command %s', $cmd));

        $process = new Process($cmd);

        try
        {
            $process->run();
        }
        catch (\RuntimeException $e)
        {

        }

        if ( ! $process->isSuccessful())
        {
            $this->logger->addError(sprintf('Command failed :: %s', $process->getErrorOutput()));

            $this->cleanupTemporaryFile($output);

            throw new Exception\RuntimeException('Failed to extract image');
        }

        $this->logger->addInfo('Command run with success');

        return true;
    }

    /**
     * Encode the file to the specified format
     *
     * @param Format\AudioFormat $format    The output format
     * @param string $outputPathfile        The pathfile where to write
     * @param int $threads                  The number of threads to use
     * @return boolean                      True if success
     * @throws Exception\RuntimeException
     */
    public function encode(Format\AudioFormat $format, $outputPathfile, $threads = 1)
    {
        if ( ! $this->pathfile)
        {
            throw new Exception\RuntimeException('No file open');
        }

        $threads = max(min($threads, 64), 1);

        switch (true)
        {
            case $format instanceof Format\VideoFormat:
                return $this->encodeVideo($format, $outputPathfile, $threads);
                break;
            default:
            case $format instanceof Format\AudioFormat:
                return $this->encodeAudio($format, $outputPathfile, $threads);
                break;
        }

        return false;
    }

    /**
     * Encode to audio
     *
     * @param Format\AudioFormat $format    The output format
     * @param string $outputPathfile        The pathfile where to write
     * @param int $threads                  The number of threads to use
     * @return boolean                      True if success
     * @throws Exception\RuntimeException
     */
    protected function encodeAudio(Format\AudioFormat $format, $outputPathfile, $threads)
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

        try
        {
            $process->run();
        }
        catch (\RuntimeException $e)
        {

        }

        if ( ! $process->isSuccessful())
        {
            throw new Exception\RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        return true;
    }

    /**
     * Encode to video
     *
     * @param Format\VideoFormat $format    The output format
     * @param string $outputPathfile        The pathfile where to write
     * @param int $threads                  The number of threads to use
     * @return boolean                      True if success
     * @throws Exception\RuntimeException
     */
    protected function encodeVideo(Format\VideoFormat $format, $outputPathfile, $threads)
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

        foreach ($passes as $pass)
        {
            $process = new Process($pass);

            try
            {
                $process->run();
            }
            catch (\RuntimeException $e)
            {
                break;
            }
        }

        $this->cleanupTemporaryFile($tmpFile->getPathname());
        $this->cleanupTemporaryFile(getcwd() . '/ffmpeg2pass-0.log');
        $this->cleanupTemporaryFile(getcwd() . '/ffmpeg2pass-0.log.mbtree');

        if ( ! $process->isSuccessful())
        {
            throw new Exception\RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        return true;
    }

    /**
     * Removes unnecessary file
     *
     * @param string $pathfile
     */
    protected function cleanupTemporaryFile($pathfile)
    {
        if (file_exists($pathfile) && is_writable($pathfile))
        {
            unlink($pathfile);
        }
    }

    /**
     * Return the binary name
     *
     * @return string
     */
    protected static function getBinaryName()
    {
        return 'ffmpeg';
    }

}
