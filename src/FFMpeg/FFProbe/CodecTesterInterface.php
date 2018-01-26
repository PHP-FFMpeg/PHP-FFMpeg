<?php
namespace FFMpeg\FFProbe;

/**
 * Implementation for classes that check whether codecs are available
 *
 * @author      jens1o
 * @copyright   Jens Hausdorf 2018
 * @license     MIT License
 * @package     FFMpeg
 * @subpackage  FFProbe
 * @since       1.0.0
 */
interface CodecTesterInterface
{

    /**
     * Returns true when the codec `$codecName` is available.
     *
     * @param string $codecName
     * @return bool
     */
    public function has(string $codecName): bool;

    /**
     * Returns all available codecs
     *
     * @return string[]
     */
    public function getAvailableCodecs(): array;
}
