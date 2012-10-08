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

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Silex\Application;
use Silex\ServiceProviderInterface;

class FFMpegServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        if (isset($app['monolog'])) {
            $app['ffmpeg.logger'] = function() use ($app) {
                return $app['monolog'];
            };
        } else {
            $app['ffmpeg.logger'] = $app->share(function(Application $app) {
                $logger = new Logger('FFMpeg logger');
                $logger->pushHandler(new NullHandler());

                return $logger;
            });
        }

        $app['ffmpeg.ffmpeg'] = $app->share(function(Application $app) {
            if (isset($app['ffmpeg.ffmpeg.binary'])) {
                $ffmpeg = new FFMpeg($app['ffmpeg.ffmpeg.binary'], $app['ffmpeg.logger']);
            } else {
                $ffmpeg = FFMpeg::load($app['ffmpeg.logger']);
            }

            return $ffmpeg
                    ->setProber($app['ffmpeg.ffprobe'])
                    ->setThreads(isset($app['ffmpeg.threads']) ? $app['ffmpeg.threads'] : 1);
        });

        $app['ffmpeg.ffprobe'] = $app->share(function(Application $app) {
            if (isset($app['ffmpeg.ffprobe.binary'])) {
                return new FFProbe($app['ffmpeg.ffprobe.binary'], $app['ffmpeg.logger']);
            } else {
                return FFProbe::load($app['ffmpeg.logger']);
            }
        });
    }

    public function boot(Application $app)
    {
    }
}
