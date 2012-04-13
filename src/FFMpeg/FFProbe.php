<?php

namespace FFMpeg;

class FFProbe extends Binary
{

    public function probeFormat($pathfile)
    {
        if ( ! is_file($pathfile))
        {

            throw new \RuntimeException($pathfile);
        }

        $cmd = $this->binary . ' ' . $pathfile . ' -show_format';

        return $this->executeProbe($cmd);
    }

    public function probeStreams($pathfile)
    {
        if ( ! is_file($pathfile))
        {
            throw new \RuntimeException($pathfile);
        }

        $cmd = $this->binary . ' ' . $pathfile . ' -show_streams';

        return $this->executeProbe($cmd);
    }

    protected function executeProbe($command)
    {
        $process = new \Symfony\Component\Process\Process($command);

        $process->run();

        if ( ! $process->isSuccessful())
        {
            throw new \RuntimeException('Failed to probe');
        }

        return $process->getOutput();
    }

    protected static function getBinaryName()
    {
        return 'ffprobe';
    }

}
