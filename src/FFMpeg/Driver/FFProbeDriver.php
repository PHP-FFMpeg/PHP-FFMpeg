<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Driver;

use Alchemy\BinaryDriver\AbstractBinary;
use Alchemy\BinaryDriver\Configuration;
use Psr\Log\LoggerInterface;

class FFProbeDriver extends AbstractBinary
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ffprobe';
    }

    /**
     * Creates an FFProbeDriver
     *
     * @param LoggerInterface $logger
     * @param array|Configuration $configuration
     *
     * @return FFProbeDriver
     */
    public static function create(LoggerInterface $logger, $configuration)
    {
        return static::load(array('avprobe', 'ffprobe'), $logger, $configuration);
    }
}
