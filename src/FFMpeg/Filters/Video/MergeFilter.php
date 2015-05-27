<?php

namespace FFMpeg\Filters\Video;


use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class MergeFilter implements VideoFilterInterface {

    /** @var integer */
    private $priority;

    /** @var array */
    private $additionalInputFiles;

    public function __construct($additionalInputFiles, $priority = 0)
    {
        $this->priority = $priority;
        $this->additionalInputFiles = $additionalInputFiles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Applies the filter on the the Video media given an format.
     *
     * @param Video          $video
     * @param VideoInterface $format
     *
     * @return array An array of arguments
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $commands = array();
        foreach($this->additionalInputFiles as $path){
            $commands[] = '-i';
            $commands[] = $path;
        }
        $commands[] = '-filter_complex';

        $filterParams = '';
        $totalInputCount = count($this->additionalInputFiles) + 1;
        for($i = 0; $i < $totalInputCount; $i++){
            $filterParams .= '[' . $i . ':0] ';
            $filterParams .= '[' . $i . ':1] ';
        }
        $filterParams .= 'concat=n=' . $totalInputCount . ':v=1:a=1 [v] [a]';

        $commands[] = $filterParams;
        $commands[] = '-map';
        $commands[] = '[v]';
        $commands[] = '-map';
        $commands[] = '[a]';
        return $commands;
    }
}
