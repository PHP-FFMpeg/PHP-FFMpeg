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
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Waveform\WaveformFilterInterface;
use FFMpeg\Filters\Waveform\WaveformFilters;

/**
 * Class Spectrum
 * Generates an audio spectrum image using FFMPeg's `showspectrumpic` command
 * @see https://ffmpeg.org/ffmpeg-filters.html#showspectrumpic
 * @author Marcus Bointon <marcus@synchromedia.co.uk>
 * @package FFMpeg\Media
 */
class Spectrum extends Waveform
{
    const DEFAULT_MODE = 'combined';
    const DEFAULT_COLOR = 'intensity';
    const DEFAULT_SCALE = 'log';
    const DEFAULT_FSCALE = 'lin';
    const DEFAULT_SATURATION = 1.0;
    const DEFAULT_WIN_FUNC = 'hann';
    const DEFAULT_ORIENTATION = 'vertical';
    const DEFAULT_GAIN = 1.0;
    const DEFAULT_LEGEND = true;
    const DEFAULT_ROTATION = 0.0;
    const DEFAULT_START = 0;
    const DEFAULT_STOP = 0;

    /**
     * Whether to generate a `combined` spectrogram for all channels, or a `separate` one for each
     * @var string
     */
    protected $mode = self::DEFAULT_MODE;
    /**
     * The color palette to use, see setColor() for options
     * @var string
     */
    protected $color = self::DEFAULT_COLOR;
    /**
     * The scale to use for color intensity
     * @var string
     */
    protected $scale = self::DEFAULT_SCALE;
    /**
     * The scale to use for the frequency axis
     * @var string
     */
    protected $fscale = self::DEFAULT_FSCALE;
    /**
     * A saturation scaling factor, between -10.0 and 10.0. Negative values invert the color palette
     * @var float
     */
    protected $saturation = self::DEFAULT_SATURATION;
    /**
     * The windowing function to use when calculating the spectrum. See setWinFunc() for options
     * @var string
     */
    protected $win_func = self::DEFAULT_WIN_FUNC;
    /**
     * Frequency axis orientation, `horizontal` or `vertical`
     * @var string
     */
    protected $orientation = self::DEFAULT_ORIENTATION;
    /**
     * Gain for calculating color values
     * @var float
     */
    protected $gain = self::DEFAULT_GAIN;
    /**
     * Whether to display axes and labels
     * @var bool
     */
    protected $legend = self::DEFAULT_LEGEND;
    /**
     * Rotation of colors within the palette, between -1.0 and 1.0
     * @var float
     */
    protected $rotation = self::DEFAULT_ROTATION;
    /**
     * Starting frequency for the spectrum in Hz. Must be positive and not greater than stop frequency
     * @var int
     */
    protected $start = self::DEFAULT_START;
    /**
     * Ending frequency for the spectrum in Hz. Must be positive and not less than start frequency
     * @var int
     */
    protected $stop = self::DEFAULT_STOP;

    /**
     * Spectrum constructor.
     *
     * @param Audio $audio
     * @param FFMpegDriver $driver
     * @param FFProbe $ffprobe
     * @param int $width
     * @param int $height
     * @param array $colors Note that this is not used, just here for compatibility with the Waveform parent
     */
    public function __construct(
        Audio $audio,
        FFMpegDriver $driver,
        FFProbe $ffprobe,
        $width,
        $height,
        $colors = array(self::DEFAULT_COLOR)
    ) {
        parent::__construct($audio, $driver, $ffprobe, $width, $height);
        $this->audio = $audio;
    }

    /**
     * Set the rendering mode.
     *
     * @param string $mode `combined` to create a single spectrogram for all channels, or `separate` for each channel separately, all within the same image
     *
     * @return $this
     */
    public function setMode($mode = 'combined')
    {
        static $modes = array(
            'combined',
            'separate',
        );
        if (! in_array($mode, $modes, true)) {
            throw new InvalidArgumentException('Unknown mode. Valid values are: ' . implode(', ', $modes));
        }
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get the current rendering mode.
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the color palette to use.
     *
     * @param string $color One of the available preset palette names: `channel`, `intensity`, `moreland`, `nebulae`, `fire`, `fiery`, `fruit`, `cool`, `magma`, `green`, `viridis`, `plasma`, `cividis`, `terrain`, or `random` to have it pick a random one
     *
     * @return $this
     */
    public function setColor($color = 'intensity')
    {
        static $modes = array(
            'channel',
            'intensity',
            'moreland',
            'nebulae',
            'fire',
            'fiery',
            'fruit',
            'cool',
            'magma',
            'green',
            'viridis',
            'plasma',
            'cividis',
            'terrain',
        );
        if ($color === 'random') {
            $this->color = array_rand($modes);

            return $this;
        }
        if (!in_array($color, $modes, true)) {
            throw new InvalidArgumentException('Unknown color mode. Valid values are: ' . implode(', ', $modes));
        }
        $this->color = $color;

        return $this;
    }

    /**
     * Get the current color palette.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the scale to use for color intensity
     *
     * @param string $scale One of `lin`, `sqrt`, `log`, `4thrt`, or `5thrt`.
     *
     * @return $this
     */
    public function setScale($scale = 'log')
    {
        static $scales = array(
            'lin',
            'sqrt',
            'log',
            '4thrt',
            '5thrt',
        );
        if (! in_array($scale, $scales, true)) {
            throw new InvalidArgumentException('Unknown scale. Valid values are: ' . implode(', ', $scales));
        }
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get the current color intensity scale.
     *
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Set the frequency axis scale.
     *
     * @param string $fscale One of `lin` or `log`.
     *
     * @return $this
     */
    public function setFscale($fscale = 'lin')
    {
        static $fscales = array(
            'lin',
            'log',
        );
        if (! in_array($fscale, $fscales, true)) {
            throw new InvalidArgumentException('Unknown fscale. Valid values are: ' . implode(', ', $fscales));
        }
        $this->fscale = $fscale;

        return $this;
    }

    /**
     * Get the current frequency axis scale.
     *
     * @return string
     */
    public function getFscale()
    {
        return $this->fscale;
    }

    /**
     * Set the color saturation scaling factor.
     *
     * @param float $saturation A value between -10.0 and 10.0 to multiply saturation values by. Negative values invert the saturation.
     *
     * @return $this
     */
    public function setSaturation($saturation = 1.0)
    {
        $saturation = (float)$saturation;
        if ($saturation < -10.0 || $saturation > 10.0) {
            throw new InvalidArgumentException('Saturation must be between -10.0 and 10.0.');
        }
        $this->saturation = $saturation;

        return $this;
    }

    /**
     * Get the current saturation scaling value.
     *
     * @return float
     */
    public function getSaturation()
    {
        return $this->saturation;
    }

    /**
     * Set the windowing function to use when calculating the spectrum.
     *
     * @param string $win_func One of `rect`, `bartlett`, `hann`, `hanning`, `hamming`, `blackman`, `welch`, `flattop`, `bharris`, `bnuttall`, `bhann`, `sine`, `nuttall`, `lanczos`, `gauss`, `tukey`, `dolph`, `cauchy`, `parzen`, `poisson`, or `bohman`.
 *
     * @return $this
     */
    public function setWinFunc($win_func = 'hann')
    {
        static $win_funcs = array(
            'rect',
            'bartlett',
            'hann',
            'hanning',
            'hamming',
            'blackman',
            'welch',
            'flattop',
            'bharris',
            'bnuttall',
            'bhann',
            'sine',
            'nuttall',
            'lanczos',
            'gauss',
            'tukey',
            'dolph',
            'cauchy',
            'parzen',
            'poisson',
            'bohman',
        );
        if (! in_array($win_func, $win_funcs, true)) {
            throw new InvalidArgumentException('Unknown win_func. Valid values are: ' . implode(', ', $win_funcs));
        }
        $this->win_func = $win_func;

        return $this;
    }

    /**
     * Get the current windowing function.
     *
     * @return string
     */
    public function getWinFunc()
    {
        return $this->win_func;
    }

    /**
     * Set the orientation of the generated spectrum.
     *
     * @param string $orientation `vertical` or `horizontal`
     *
     * @return $this
     */
    public function setOrientation($orientation = 'vertical')
    {
        static $orientations = array(
            'vertical',
            'horizontal',
        );
        if (! in_array($orientation, $orientations, true)) {
            throw new InvalidArgumentException(
                'Unknown orientation. Valid values are: ' . implode(', ', $orientations)
            );
        }
        $this->orientation = $orientation;

        return $this;
    }

    /**
     * Get the current orientation.
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set the gain used for calculating colour values.
     *
     * @param float $gain A multiplying factor: Use larger values for files with quieter audio.
     *
     * @return $this
     */
    public function setGain($gain = 1.0)
    {
        $this->gain = (float)$gain;

        return $this;
    }

    /**
     * Get the current colour gain factor.
     *
     * @return float
     */
    public function getGain()
    {
        return $this->gain;
    }

    /**
     * Turn the graph legends (axes and scales) on and off.
     *
     * @param bool $legend
     *
     * @return $this
     */
    public function setLegend($legend = true)
    {
        $this->legend = (bool)$legend;

        return $this;
    }

    /**
     * Get the current legend state.
     *
     * @return bool
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * Set the color rotation value. This rotates the colour palette, not the resulting image.
     *
     * @param float $rotation
     *
     * @return $this
     */
    public function setRotation($rotation = 0.0)
    {
        $rotation = (float)$rotation;
        if ($rotation < -1.0 || $rotation > 1.0) {
            throw new InvalidArgumentException('Color rotation must be between -1.0 and 1.0.');
        }
        $this->rotation = $rotation;

        return $this;
    }

    /**
     * Get the color palette rotation value.
     *
     * @return float
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * Set the starting frequency for the spectrum.
     *
     * @param int $start The starting frequency, in Hz. Must be positive and not greater than the stop frequency.
     *
     * @return $this
     */
    public function setStart($start = 0)
    {
        $start = (int)abs($start);
        if ($start > $this->stop) {
            throw new InvalidArgumentException('Start frequency must be lower than stop frequency.');
        }
        $this->start = $start;

        return $this;
    }

    /**
     * Get the current starting frequency.
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the ending frequency for the spectrum.
     *
     * @param int $stop The ending frequency, in Hz. Must be positive and not less than the start frequency.
     *
     * @return $this
     */
    public function setStop($stop = 0)
    {
        $stop = (int)abs($stop);
        if ($stop < $this->start) {
            throw new InvalidArgumentException('Stop frequency must be higher than start frequency.');
        }
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get the current ending frequency.
     *
     * @return int
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Compile options into a parameter string
     *
     * @return string
     */
    protected function compileOptions()
    {
        $params = array();
        $params[] = 's=' . $this->width . 'x' . $this->height;
        if ($this->mode !== self::DEFAULT_MODE) {
            $params[] = 'mode=' . $this->mode;
        }
        if ($this->color !== self::DEFAULT_COLOR) {
            $params[] = 'color=' . $this->color;
        }
        if ($this->scale !== self::DEFAULT_SCALE) {
            $params[] = 'scale=' . $this->scale;
        }
        if ($this->fscale !== self::DEFAULT_FSCALE) {
            $params[] = 'fscale=' . $this->fscale;
        }
        if ($this->saturation !== self::DEFAULT_SATURATION) {
            $params[] = 'saturation=' . $this->saturation;
        }
        if ($this->win_func !== self::DEFAULT_WIN_FUNC) {
            $params[] = 'win_func=' . $this->win_func;
        }
        if ($this->orientation !== self::DEFAULT_ORIENTATION) {
            $params[] = 'orientation=' . $this->orientation;
        }
        if ($this->gain !== self::DEFAULT_GAIN) {
            $params[] = 'gain=' . $this->gain;
        }
        if ($this->legend !== self::DEFAULT_LEGEND) {
            $params[] = 'legend=' . ($this->legend ? '1' : '0');
        }
        if ($this->rotation !== self::DEFAULT_ROTATION) {
            $params[] = 'rotation=' . $this->rotation;
        }
        if ($this->start !== self::DEFAULT_START) {
            $params[] = 'start=' . $this->start;
        }
        if ($this->stop !== self::DEFAULT_STOP) {
            $params[] = 'stop=' . $this->stop;
        }
        return implode(':', $params);
    }

    /**
     * Generates and saves the spectrum in the given filename.
     *
     * @param string $pathfile
     *
     * @return Spectrum
     *
     * @throws RuntimeException
     */
    public function save($pathfile)
    {
        /**
         * might be optimized with http://ffmpeg.org/trac/ffmpeg/wiki/Seeking%20with%20FFmpeg
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        $commands = array(
            '-y', //Overwrite output files
            '-i', //Specify input file
            $this->pathfile,
            '-filter_complex', //Say we want a complex filter
            'showspectrumpic=' . $this->compileOptions(), //Specify the filter and its params
            '-frames:v', //Stop writing output...
            1 // after 1 "frame"
        );

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        $commands = array_merge($commands, array($pathfile));

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save spectrum', $e->getCode(), $e);
        }

        return $this;
    }
}
