<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Driver;

use Alchemy\BinaryDriver\{
    AbstractBinary,
    Configuration,
    ConfigurationInterface
};
use Alchemy\BinaryDriver\Exception\ExecutableNotFoundException as BinaryDriverExecutableNotFound;
use FFMpeg\Exception\{
    ExecutableNotFoundException,
    InvalidArgumentException
};
use Psr\Log\LoggerInterface;

class FFMpegDriver extends AbstractBinary
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'ffmpeg';
    }

    /**
     * Creates an FFMpegDriver.
     *
     * @param   LoggerInterface|null    $logger
     * @param   array|Configuration     $configuration
     *
     * @return FFMpegDriver
     *
     * @throws InvalidArgumentException
     * @throws ExecutableNotFoundException
     */
    public static function create(?LoggerInterface $logger = null, $configuration = []): FFMpegDriver
    {
        if (!($configuration instanceof ConfigurationInterface)) {
            if (!\is_array($configuration)) {
                $givenType = \gettype($configuration);
                throw new InvalidArgumentException(
                    "The \$configuration Parameter must either be an array or an instance of ConfigurationInterface, {$givenType} given."
                );
            }

            $configuration = new Configuration($configuration);
        }

        $binaries = $configuration->get('ffmpeg.binaries', ['ffmpeg', 'avconv']);

        if (!$configuration->has('timeout')) {
            $configuration->set('timeout', 300);
        }

        try {
            return static::load($binaries, $logger, $configuration);
        } catch (BinaryDriverExecutableNotFound $e) {
            throw new ExecutableNotFoundException('Unable to load FFMpeg', $e->getCode(), $e);
        }
    }
}
