<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Akuma <meru.no.akuma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Driver;

use Alchemy\BinaryDriver\AbstractBinary;
use Alchemy\BinaryDriver\Configuration;
use Alchemy\BinaryDriver\ConfigurationInterface;
use Alchemy\BinaryDriver\Exception\ExecutableNotFoundException as BinaryDriverExecutableNotFound;
use FFMpeg\Exception\ExecutableNotFoundException;
use Psr\Log\LoggerInterface;

class QtFaststartDriver extends AbstractBinary
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'qtfaststart';
    }

    /**
     * Creates an QtFaststart.
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface              $logger
     *
     * @return QtFaststart
     */
    public static function create($configuration, LoggerInterface $logger = null)
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        $binaries = $configuration->get('qtfaststart.binaries', array('qt-faststart'));

        try {
            return static::load($binaries, $logger, $configuration);
        } catch (BinaryDriverExecutableNotFound $e) {
            throw new ExecutableNotFoundException('Unable to load QtFaststart', $e->getCode(), $e);
        }
    }
}
