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
use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Format\Video\Resamplable as VideoResamplable;
use FFMpeg\Format\Video\Resizable as VideoResizable;
use FFMpeg\Format\Video\Transcodable as VideoTranscodable;
use FFMpeg\Format\Audio\Resamplable as AudioResamplable;
use FFMpeg\Format\Audio\Transcodable as AudioTranscodable;
use FFMpeg\Helper\HelperInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

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
    protected $threads = 1;

    /**
     * @var HelperInterface[]
     */
    protected $helpers = array();

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->prober = null;
        parent::__destruct();
    }

    /**
     * @param HelperInterface $helper
     * @return \FFMpeg\FFMpeg
     */
    public function attachHelper(HelperInterface $helper)
    {
        $this->helpers[] = $helper;
        $helper->setProber($this->prober);

        // ensure the helpers have the path to the file in case
        // they need to probe for format information
        if ($this->pathfile !== null) {
            $helper->open($this->pathfile);
        }

        return $this;
    }

    public function setThreads($threads)
    {
        if ($threads > 64 || $threads < 1) {
            throw new InvalidArgumentException('Invalid `threads` value ; threads must fit in range 1 - 64');
        }

        $this->threads = (int) $threads;

        return $this;
    }

    public function getThreads()
    {
        return $this->threads;
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
        if (!file_exists($pathfile)) {
            $this->logger->addError(sprintf('FFmpeg failed to open %s', $pathfile));

            throw new InvalidArgumentException(sprintf('File %s does not exists', $pathfile));
        }

        $this->logger->addInfo(sprintf('FFmpeg opens %s', $pathfile));
        $this->pathfile = $pathfile;

        foreach ($this->helpers as $helper) {
            $helper->open($pathfile);
        }

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
     * @param  integer|string   $time     The time where to take the snapshot, time could either be in second or in hh:mm:ss[.xxx] form.
     * @param  string           $output   The pathfile where to write
     * @param  Boolean          $accurate Whether to decode the whole video until position or seek and extract. See -ss option in FFMpeg manual (http://ffmpeg.org/ffmpeg.html#Main-options)
     *
     * @return \FFMpeg\FFMpeg
     *
     * @throws RuntimeException
     * @throws LogicException
     */
    public function extractImage($time, $output, $accurate = false)
    {
        if (!$this->pathfile) {
            throw new LogicException('No file open');
        }

        /**
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        if (!$accurate) {
            $options = array(
                $this->binary, '-ss', $time,
                '-i', $this->pathfile,
                '-vframes', '1',
                '-f', 'image2', $output
            );
        } else {
            $options = array(
                $this->binary,
                '-i', $this->pathfile,
                '-vframes', '1', '-ss', $time,
                '-f', 'image2', $output
            );
        }

        $builder = ProcessBuilder::create($options);
        $process = $builder->getProcess();
        $process->setTimeout($this->timeout);


        $this->logger->addInfo(sprintf('FFmpeg executes command %s', $process->getCommandline()));

        try {
            $process->run(array($this, 'transcodeCallback'));
        } catch (\RuntimeException $e) {

        }

        if (!$process->isSuccessful()) {
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
     * @param  AudioInterface   $format         The output format
     * @param  string           $outputPathfile The pathfile where to write
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     * @throws LogicException
     */
    public function encode(AudioInterface $format, $outputPathfile)
    {
        if (!$this->pathfile) {
            throw new LogicException('No file open');
        }

        switch (true) {
            case $format instanceof VideoInterface:
                $this->encodeVideo($format, $outputPathfile);
                break;
            default:
            case $format instanceof AudioInterface:
                $this->encodeAudio($format, $outputPathfile);
                break;
        }

        return $this;
    }

    /**
     * Encode to audio
     *
     * @param  Audio            $format         The output format
     * @param  string           $outputPathfile The pathfile where to write
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     */
    protected function encodeAudio(AudioInterface $format, $outputPathfile)
    {
        $builder = ProcessBuilder::create(array(
                $this->binary,
                '-y', '-i',
                $this->pathfile,
                '-threads', $this->threads,
                '-ab', $format->getKiloBitrate() . 'k ',
            ));

        foreach ($format->getExtraParams() as $parameter) {
            $builder->add($parameter);
        }

        if ($format instanceof AudioTranscodable) {
            $builder->add('-acodec')->add($format->getAudioCodec());
        }

        if ($format instanceof AudioResamplable) {
            $builder->add('-ac')->add(2)->add('-ar')->add($format->getAudioSampleRate());
        }

        $builder->add($outputPathfile);

        $process = $builder->getProcess();

        $this->logger->addInfo(sprintf('FFmpeg executes command %s', $process->getCommandLine()));

        try {
            $process->run(array($this, 'transcodeCallback'));
        } catch (\RuntimeException $e) {

        }

        if (!$process->isSuccessful()) {
            $this->logger->addInfo(sprintf('FFmpeg command failed'));
            throw new RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        $this->logger->addInfo(sprintf('FFmpeg command successful'));

        return $this;
    }

    /**
     * Encode to video
     *
     * @param  VideoInterface   $format         The output format
     * @param  string           $outputPathfile The pathfile where to write
     * @return \FFMpeg\FFMpeg
     * @throws RuntimeException
     */
    protected function encodeVideo(VideoInterface $format, $outputPathfile)
    {
        $builder = ProcessBuilder::create(array(
                $this->binary, '-y', '-i',
                $this->pathfile
            ));

        foreach ($format->getExtraParams() as $parameter) {
            $builder->add($parameter);
        }

        if ($format instanceof VideoResizable) {
            if (!$this->prober) {
                throw new LogicException('You must set a valid prober if you use a resizable format');
            }

            $result = json_decode($this->prober->probeStreams($this->pathfile), true);

            $originalWidth = $originalHeight = null;

            foreach ($result as $stream) {
                foreach ($stream as $name => $value) {
                    if ($name == 'width') {
                        $originalWidth = $value;
                        continue;
                    }
                    if ($name == 'height') {
                        $originalHeight = $value;
                        continue;
                    }
                }
            }

            if ($originalHeight !== null && $originalWidth !== null) {
                $this->logger->addInfo(sprintf('Read dimension for resizin succesful : %s x %s', $originalWidth, $originalHeight));
            } else {
                $this->logger->addInfo(sprintf('Read dimension for resizin failed !'));
            }

            if ($originalHeight !== null && $originalWidth !== null) {
                $dimensions = $format->getComputedDimensions($originalWidth, $originalHeight);

                $width = $this->getMultiple($dimensions->getWidth(), 16);
                $height = $this->getMultiple($dimensions->getHeight(), 16);

                $builder->add('-s')->add($width . 'x' . $height);
            }
        }


        if ($format instanceof VideoResamplable) {
            $builder->add('-r')->add($format->getFrameRate());

            /**
             * @see http://sites.google.com/site/linuxencoding/x264-ffmpeg-mapping
             */
            if ($format->supportBFrames()) {
                $builder->add('-b_strategy')
                    ->add('1')
                    ->add('-bf')
                    ->add('3')
                    ->add('-g')
                    ->add($format->getGOPSize());
            }
        }

        if ($format instanceof VideoTranscodable) {
            $builder->add('-vcodec')->add($format->getVideoCodec());
        }

        $builder->add('-b:v')->add($format->getKiloBitrate() . 'k')
            ->add('-threads')->add($this->threads)
            ->add('-refs')->add('6')
            ->add('-coder')->add('1')
            ->add('-sc_threshold')->add('40')
            ->add('-flags')->add('+loop')
            ->add('-me_range')->add('16')
            ->add('-subq')->add('7')
            ->add('-i_qfactor')->add('0.71')
            ->add('-qcomp')->add('0.6')
            ->add('-qdiff')->add('4')
            ->add('-trellis')->add('1')
            ->add('-b:a')->add('92k');

        if ($format instanceof AudioTranscodable) {
            $builder->add('-acodec')->add($format->getAudioCodec());
        }

        $passPrefix = uniqid('pass-');
        
        $pass1 = $builder;
        $pass2 = clone $builder;

        $passes[] = $pass1
            ->add('-pass')->add('1')
            ->add('-passlogfile')->add($passPrefix)
            ->add('-an')->add($outputPathfile)
            ->getProcess();
        $passes[] = $pass2
            ->add('-pass')->add('2')
            ->add('-passlogfile')->add($passPrefix)
            ->add('-ac')->add('2')
            ->add('-ar')->add('44100')->add($outputPathfile)
            ->getProcess();

        foreach ($passes as $process) {

            $this->logger->addInfo(sprintf('FFmpeg executes command %s', $process->getCommandline()));

            try {
                $process->run(array($this, 'transcodeCallback'));
            } catch (\RuntimeException $e) {
                break;
            }
        }

        $this->cleanupTemporaryFile(getcwd() . '/' . $passPrefix . '-0.log');
        $this->cleanupTemporaryFile(getcwd() . '/' . $passPrefix . '-0.log');
        $this->cleanupTemporaryFile(getcwd() . '/' . $passPrefix . '-0.log.mbtree');

        if (!$process->isSuccessful()) {
            $this->logger->addInfo(sprintf('FFmpeg command failed'));
            throw new RuntimeException(sprintf('Encoding failed : %s', $process->getErrorOutput()));
        }

        $this->logger->addInfo(sprintf('FFmpeg command successful'));

        return $this;
    }

    /**
     * The main transcoding callback, delegates the content to the helpers.
     *
     * @param string $channel (stdio|stderr)
     * @param string $content the current line of the ffmpeg output
     */
    public function transcodeCallback($channel, $content)
    {
        foreach ($this->helpers as $helper) {
            $helper->transcodeCallback($channel, $content);
        }
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
