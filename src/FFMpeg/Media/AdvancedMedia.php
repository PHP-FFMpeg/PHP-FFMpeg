<?php

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe;
use FFMpeg\Filters\AdvancedMedia\ComplexCompatibleFilter;
use FFMpeg\Filters\AdvancedMedia\ComplexFilterContainer;
use FFMpeg\Filters\AdvancedMedia\ComplexFilterInterface;
use FFMpeg\Filters\AdvancedMedia\ComplexFilters;
use FFMpeg\Filters\FiltersCollection;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\ProgressListener\AbstractProgressListener;
use FFMpeg\Format\VideoInterface;

/**
 * AdvancedMedia may have multiple inputs and multiple outputs.
 * This class accepts only filters for -filter_complex option.
 * But you can set initial and additional parameters of the ffmpeg command.
 *
 * @see http://trac.ffmpeg.org/wiki/Creating%20multiple%20outputs
 */
class AdvancedMedia extends AbstractMediaType
{
    /**
     * @var string[]
     */
    private $inputs;

    /**
     * @var string[]
     */
    private $initialParameters;

    /**
     * @var string[]
     */
    private $additionalParameters;

    /**
     * @var string[]
     */
    private $mapCommands;

    /**
     * @var AbstractProgressListener[]
     */
    private $listeners;

    /**
     * AdvancedMedia constructor.
     *
     * @param string[]     $inputs Array of files to be opened.
     * @param FFMpegDriver $driver
     * @param FFProbe      $ffprobe
     */
    public function __construct($inputs, FFMpegDriver $driver, FFProbe $ffprobe)
    {
        // In case of error user will see this text in the error log.
        // But absence of inputs is a correct situation for some cases.
        // For example, if the user will use filters such as "testsrc".
        $pathfile = 'you_can_pass_empty_inputs_array_only_if_you_use_computed_inputs';
        $inputsKeys = array_keys($inputs);
        if (count($inputsKeys) > 0) {
            $pathfile = $inputs[$inputsKeys[0]];
        }

        parent::__construct($pathfile, $driver, $ffprobe);
        $this->filters = new FiltersCollection();
        $this->inputs = $inputs;
        $this->initialParameters = array();
        $this->additionalParameters = array();
        $this->mapCommands = array();
        $this->listeners = array();
    }

    /**
     * Returns the available filters.
     *
     * @return ComplexFilters
     */
    public function filters()
    {
        return new ComplexFilters($this);
    }

    /**
     * Add complex filter.
     *
     * @param string                  $in
     * @param ComplexCompatibleFilter $filter
     * @param string                  $out
     *
     * @return $this
     */
    public function addFilter($in, ComplexCompatibleFilter $filter, $out)
    {
        $this->filters->add(new ComplexFilterContainer($in, $filter, $out));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFiltersCollection(FiltersCollection $filters)
    {
        foreach ($filters as $filter) {
            if (!($filter instanceof ComplexFilterInterface)) {
                throw new RuntimeException ('For AdvancedMedia you can set filters collection'
                    . ' contains only objects that implement ComplexFilterInterface!');
            }
        }

        return parent::setFiltersCollection($filters);
    }

    /**
     * @return string[]
     */
    public function getInitialParameters()
    {
        return $this->initialParameters;
    }

    /**
     * @param string[] $initialParameters
     *
     * @return AdvancedMedia
     */
    public function setInitialParameters(array $initialParameters)
    {
        $this->initialParameters = $initialParameters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAdditionalParameters()
    {
        return $this->additionalParameters;
    }

    /**
     * @param string[] $additionalParameters
     *
     * @return AdvancedMedia
     */
    public function setAdditionalParameters(array $additionalParameters)
    {
        $this->additionalParameters = $additionalParameters;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * @return int
     */
    public function getInputsCount()
    {
        return count($this->inputs);
    }

    /**
     * @return string
     */
    public function getFinalCommand()
    {
        return implode(' ', $this->buildCommand());
    }

    /**
     * Select the streams for output.
     *
     * @param string[]        $outs           Output labels of the -filter_complex part.
     * @param FormatInterface $format         Format of the output file.
     * @param string          $outputFilename Output filename.
     * @param bool            $forceDisableAudio
     * @param bool            $forceDisableVideo
     *
     * @return $this
     * @see https://ffmpeg.org/ffmpeg.html#Manual-stream-selection
     */
    public function map(
        array $outs,
        FormatInterface $format,
        $outputFilename,
        $forceDisableAudio = false,
        $forceDisableVideo = false
    ) {
        $commands = array();
        foreach ($outs as $label) {
            $commands[] = '-map';
            $commands[] = $label;
        }

        // Apply format params.
        $commands = array_merge($commands,
            $this->applyFormatParams($format, $forceDisableAudio, $forceDisableVideo));

        // Set output file.
        $commands[] = $outputFilename;

        // Create a listener.
        if ($format instanceof ProgressableInterface) {
            $listener = $format->createProgressListener($this, $this->ffprobe, 1, 1, 0);
            $this->listeners = array_merge($this->listeners, $listener);
        }

        $this->mapCommands = array_merge($this->mapCommands, $commands);
        return $this;
    }

    /**
     * Apply added filters and execute ffmpeg command.
     *
     * @return void
     * @throws RuntimeException
     */
    public function save()
    {
        $this->assertFiltersAreCompatibleToCurrentFFMpegVersion();
        $command = $this->buildCommand();

        try {
            $this->driver->command($command, false, $this->listeners);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('Encoding failed', $e->getCode(), $e);
        }
    }

    /**
     * @param FormatInterface $format
     * @param bool            $forceDisableAudio
     * @param bool            $forceDisableVideo
     *
     * @return array
     */
    private function applyFormatParams(
        FormatInterface $format,
        $forceDisableAudio = false,
        $forceDisableVideo = false
    ) {
        // Set format params.
        $commands = array();
        if (!$forceDisableVideo && $format instanceof VideoInterface) {
            if ($format->getVideoCodec() !== null) {
                $commands[] = '-vcodec';
                $commands[] = $format->getVideoCodec();
            }
            // If the user passed some additional format parameters.
            if ($format->getAdditionalParameters() !== null) {
                $commands = array_merge($commands, $format->getAdditionalParameters());
            }
        }
        if (!$forceDisableAudio && $format instanceof AudioInterface) {
            if ($format->getAudioCodec() !== null) {
                $commands[] = '-acodec';
                $commands[] = $format->getAudioCodec();
            }
            if ($format->getAudioKiloBitrate() !== null) {
                $commands[] = '-b:a';
                $commands[] = $format->getAudioKiloBitrate() . 'k';
            }
            if ($format->getAudioChannels() !== null) {
                $commands[] = '-ac';
                $commands[] = $format->getAudioChannels();
            }
        }

        // If the user passed some extra parameters.
        if ($format->getExtraParams()) {
            $commands = array_merge($commands, $format->getExtraParams());
        }

        return $commands;
    }

    /**
     * @param ComplexFilterInterface $filter
     *
     * @return string
     */
    private function applyComplexFilter(ComplexFilterInterface $filter)
    {
        /** @var $format VideoInterface */
        $filterCommands = $filter->applyComplex($this);
        foreach ($filterCommands as $index => $command) {
            if ($command === '-vf' || $command === '-filter:v' || $command === '-filter_complex') {
                unset ($filterCommands[$index]);
            }
        }

        $strCommand = implode(' ', $filterCommands);

        // Compatibility with the some existed filters:
        // If the command contains [in], just replace it to inLabel. If not - to add it manually.
        if (stripos($strCommand, '[in]') !== false) {
            $strCommand = str_replace('[in]', $filter->getInLabels(), $strCommand);
            $in = '';
        } else {
            $in = $filter->getInLabels();
        }

        // If the command contains [out], just replace it to outLabel. If not - to add it manually.
        if (stripos($strCommand, '[out]') !== false) {
            $strCommand = str_replace('[out]', $filter->getOutLabels(), $strCommand);
            $out = '';
        } else {
            $out = $filter->getOutLabels();
        }

        return $in . $strCommand . $out;
    }

    /**
     * @return void
     * @throws RuntimeException
     */
    protected function assertFiltersAreCompatibleToCurrentFFMpegVersion()
    {
        $messages = array();
        $currentVersion = $this->getFFMpegDriver()->getVersion();
        /** @var ComplexFilterInterface $filter */
        foreach ($this->filters as $filter) {
            if (version_compare($currentVersion, $filter->getMinimalFFMpegVersion(), '<')) {
                $messages[] = $filter->getName() . ' filter is supported starting from '
                    . $filter->getMinimalFFMpegVersion() . ' ffmpeg version';
            }
        }

        if (!empty($messages)) {
            throw new RuntimeException(implode('; ', $messages)
                . '; your ffmpeg version is ' . $currentVersion);
        }
    }

    /**
     * @return array
     */
    protected function buildCommand()
    {
        $globalOptions = array('threads', 'filter_threads', 'filter_complex_threads');
        return array_merge(array('-y'),
            $this->buildConfiguredGlobalOptions($globalOptions),
            $this->getInitialParameters(),
            $this->buildInputsPart($this->inputs),
            $this->buildComplexFilterPart($this->filters),
            $this->mapCommands,
            $this->getAdditionalParameters()
        );
    }

    /**
     * @param string[] $optionNames
     *
     * @return array
     */
    private function buildConfiguredGlobalOptions($optionNames)
    {
        $commands = array();
        foreach ($optionNames as $optionName) {
            if (!$this->driver->getConfiguration()->has('ffmpeg.' . $optionName)) {
                continue;
            }

            $commands[] = '-' . $optionName;
            $commands[] = $this->driver->getConfiguration()->get('ffmpeg.' . $optionName);
        }

        return $commands;
    }

    /**
     * Build inputs part of the ffmpeg command.
     *
     * @param string[] $inputs
     *
     * @return array
     */
    private function buildInputsPart(array $inputs)
    {
        $commands = array();
        foreach ($inputs as $input) {
            $commands[] = '-i';
            $commands[] = $input;
        }

        return $commands;
    }

    /**
     * Build "-filter_complex" part of the ffmpeg command.
     *
     * @param FiltersCollection $complexFilters
     *
     * @return array
     */
    private function buildComplexFilterPart(FiltersCollection $complexFilters)
    {
        $commands = array();
        /** @var ComplexFilterInterface $filter */
        foreach ($complexFilters as $filter) {
            $filterCommand = $this->applyComplexFilter($filter);
            $commands[] = $filterCommand;
        }

        if (empty($commands)) {
            return array();
        }
        return array('-filter_complex', implode(';', $commands));
    }
}
