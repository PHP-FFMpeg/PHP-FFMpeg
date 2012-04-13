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

class FFProbe extends Binary
{

    public function probeFormat($pathfile)
    {
        if ( ! is_file($pathfile))
        {
            throw new \InvalidArgumentException($pathfile);
        }

        $cmd = $this->binary . ' ' . $pathfile . ' -show_format';

        return $this->executeProbe($cmd);
    }

    public function probeStreams($pathfile)
    {
        if ( ! is_file($pathfile))
        {
            throw new \InvalidArgumentException($pathfile);
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
            throw new \RuntimeException(sprintf('Failed to probe %s', $command));
        }

        return $process->getOutput();
    }

    protected static function getBinaryName()
    {
        return 'ffprobe';
    }

}
