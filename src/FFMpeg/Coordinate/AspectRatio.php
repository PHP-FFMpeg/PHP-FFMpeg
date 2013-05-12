<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Coordinate;

use FFMpeg\Exception\InvalidArgumentException;

class AspectRatio
{
    // named 4:3 or 1.33:1 Traditional TV
    const AR_4_3 = '4/3';
    // named 16:9 or 1.77:1 HD video standard
    const AR_16_9 = '16/9';

    // named 3:2 or 1.5:1 see http://en.wikipedia.org/wiki/135_film
    const AR_3_2 = '3/2';
    // named 5:3 or 1.66:1 see http://en.wikipedia.org/wiki/Super_16_mm
    const AR_5_3 = '5/3';

    // mostly used in Photography
    const AR_5_4 = '5/4';
    const AR_1_1 = '1/1';

    // 1.85:1 US widescreen cinema standard see http://en.wikipedia.org/wiki/Widescreen#Film
    const AR_1_DOT_85_1 = '1.85:1';
    // 2.39:1 or 2.40:1 Current widescreen cinema standard see http://en.wikipedia.org/wiki/Anamorphic_format
    const AR_2_DOT_39_1 = '2.39:1';

    // Rotated constants

    // Rotated 4:3
    const AR_ROTATED_3_4 = '3/4';
    // Rotated 16:9
    const AR_ROTATED_9_16 = '9/16';

    // Rotated 3:2
    const AR_ROTATED_2_3 = '2/3';
    // Rotated 5:3
    const AR_ROTATED_3_5 = '3/5';

    // Rotated 5:4
    const AR_ROTATED_4_5 = '4/5';

    // Rotated 1.85
    const AR_ROTATED_1_DOT_85 = '1/1.85';
    // Rotated 2.39
    const AR_ROTATED_2_DOT_39 = '1/2.39';

    const STRATEGY_NEAREST = 1;
    const STRATEGY_CUSTOM = 2;

    private $ratio;

    public function __construct($ratio)
    {
        $this->ratio = $ratio;
    }

    public function getValue()
    {
        return $this->ratio;
    }

    /**
     *
     * @param Dimension $dimension
     * @param Integer $strategy
     *
     * @return AspectRatio
     *
     * @throws InvalidArgumentException
     */
    public static function fromDimensions(Dimension $dimension, $strategy)
    {
        $incoming = $dimension->getWidth() / $dimension->getHeight();

        switch ($strategy) {
            case static::STRATEGY_NEAREST:
                return new static(static::nearestStrategy($incoming));
            case static::STRATEGY_CUSTOM:
                return new static(static::customStrategy($incoming));
            default:
                throw new InvalidArgumentException(sprintf('Invalid strategy %s', $strategy));
        }
    }

    private static function valueFromName($name)
    {
        switch ($name) {
            case static::AR_4_3:
                return 4 / 3;
            case static::AR_16_9:
                return 16 / 9;
            case static::AR_1_1:
                return 1 / 1;
            case static::AR_1_DOT_85_1:
                return 1.85;
            case static::AR_2_DOT_39_1:
                return 2.39;
            case static::AR_3_2:
                return 3 / 2;
            case static::AR_5_3:
                return 5 / 3;
            case static::AR_5_4:
                return 5 / 4;
            case static::AR_ROTATED_3_4:
                return 3 / 4;
            case static::AR_ROTATED_9_16:
                return 9 / 16;
            case static::AR_ROTATED_2_3:
                return 2 / 3;
            case static::AR_ROTATED_3_5:
                return 3 / 5;
            case static::AR_ROTATED_4_5:
                return 4 / 5;
            case static::AR_ROTATED_1_DOT_85:
                return 1 / 1.85;
            case static::AR_ROTATED_2_DOT_39:
                return 1 / 2.39;
            default:
                throw new InvalidArgumentException(sprintf('Unable to find value for %s', $name));
        }
    }

    private static function customStrategy($incoming)
    {
        $try = static::nearestStrategy($incoming);

        if (abs($try - $incoming) < $try * 0.05) {
            return $try;
        }

        return $incoming;
    }

    private static function nearestStrategy($incoming)
    {
        $availables = array(
            static::AR_4_3 => static::valueFromName(static::AR_4_3),
            static::AR_16_9 => static::valueFromName(static::AR_16_9),
            static::AR_1_1 => static::valueFromName(static::AR_1_1),
            static::AR_1_DOT_85_1 => static::valueFromName(static::AR_1_DOT_85_1),
            static::AR_2_DOT_39_1 => static::valueFromName(static::AR_2_DOT_39_1),
            static::AR_3_2 => static::valueFromName(static::AR_3_2),
            static::AR_5_3 => static::valueFromName(static::AR_5_3),
            static::AR_5_4 => static::valueFromName(static::AR_5_4),

            // Rotated
            static::AR_ROTATED_4_5 => static::valueFromName(static::AR_ROTATED_4_5),
            static::AR_ROTATED_9_16 => static::valueFromName(static::AR_ROTATED_9_16),
            static::AR_ROTATED_2_3 => static::valueFromName(static::AR_ROTATED_2_3),
            static::AR_ROTATED_3_5 => static::valueFromName(static::AR_ROTATED_3_5),
            static::AR_ROTATED_3_4 => static::valueFromName(static::AR_ROTATED_3_4),
            static::AR_ROTATED_1_DOT_85 => static::valueFromName(static::AR_ROTATED_1_DOT_85),
            static::AR_ROTATED_2_DOT_39 => static::valueFromName(static::AR_ROTATED_2_DOT_39),
        );
        asort($availables);

        $previous = $current = null;

        foreach ($availables as $name => $value) {
            $current = $value;
            if ($incoming <= $value) {
                break;
            }
            $previous = $value;
        }

        if (null === $previous) {
            return $current;
        }

        if (($current - $incoming) < ($incoming - $previous)) {
            return $current;
        } else {
            return $previous;
        }
    }
}
