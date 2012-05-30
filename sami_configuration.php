<?php

include 'vendor/autoload.php';

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = 'src')
;

return new Sami($iterator, array(
    'title'                => 'PHP FFMpeg API',
    'build_dir'            => __DIR__.'/docs/API',
    'cache_dir'            => __DIR__.'/docs/API/cache',
));
