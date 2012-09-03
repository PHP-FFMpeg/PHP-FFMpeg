<?php

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
        if ( ! isset($app['ffmpeg.logger'])) {
            $app['ffmpeg.logger'] = $app->share(function(Application $app) {
                $logger = new Logger('FFMpeg logger');
                $logger->pushHandler(new NullHandler());

                return $logger;
            });
        }

        $app['ffmpeg.ffmpeg'] = $app->share(function(Application $app) {
            if (isset($app['ffmpeg.ffmpeg.binary'])) {
                return new FFMpeg($app['ffmpeg.ffmpeg.binary'], $app['ffmpeg.logger']);
            } else {
                return FFMpeg::load($app['ffmpeg.logger']);
            }
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
