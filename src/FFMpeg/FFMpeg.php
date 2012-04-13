<?php

namespace FFMpeg;

class FFMpeg extends Binary
{

    protected $pathfile;

    public function open($pathfile)
    {
        if ( ! file_exists($pathfile))
        {
            $this->logger->addError(sprintf('Request to open %s failed', $pathfile));
            throw new \InvalidArgumentException(sprintf('File %s does not exists', $pathfile));
        }

        $this->logger->addInfo(sprintf('FFmpeg opens %s', $pathfile));

        $this->pathfile = $pathfile;
    }

    public function extractImage($time, $output, $width, $height)
    {
        if ( ! $this->pathfile)
        {
            throw new \RuntimeException('No file open');
        }

        $cmd = $this->binary
          . ' -i ' . escapeshellarg($this->pathfile)
          . ' -vframes 1 -ss ' . $time
          . ' -f image2 ' . escapeshellarg($output);

        $this->logger->addInfo(sprintf('Executing command %s', $cmd));

        $process = new \Symfony\Component\Process\Process($cmd);
        $process->run();

        if ( ! $process->isSuccessful())
        {
            $this->logger->addError(sprintf('Command failed :: %s', $process->getErrorOutput()));

            if (file_exists($output) && is_writable($output))
            {
                unlink($output);
            }

            throw new \RuntimeException('Failed to extract image');
        }

        $this->logger->addInfo('Command run with success');

        return true;
    }

    public function encode(Format\AudioFormat $format, $outputPathfile, $threads = 1)
    {
        if ( ! $this->pathfile)
        {
            throw new \RuntimeException('No file open');
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

        $process = new \Symfony\Component\Process\Process($cmd);
        $process->run();

        if ( ! $process->isSuccessful())
        {
            throw new \RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        return true;
    }

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

        foreach ($passes as $pass)
        {
            $process = new \Symfony\Component\Process\Process($pass);

            try
            {
                $process->run();
            }
            catch (\Exception $e)
            {
                break;
            }
        }

        $this->cleanupTemporaryFile($tmpFile->getPathname());
        $this->cleanupTemporaryFile(getcwd() . '/ffmpeg2pass-0.log');
        $this->cleanupTemporaryFile(getcwd() . '/ffmpeg2pass-0.log.mbtree');

        if ($process instanceof \Symfony\Component\Process\Process && ! $process->isSuccessful())
        {
            throw new \RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        return true;
    }

    protected function cleanupTemporaryFile($pathfile)
    {
        if (file_exists($pathfile) && is_writable($pathfile))
        {
            unlink($pathfile);
        }
    }

    protected static function getBinaryName()
    {
        return 'ffmpeg';
    }

}
