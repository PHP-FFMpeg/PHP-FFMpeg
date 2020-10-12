<?php

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__.'/../src/');

return new Doctum($iterator, [
    'title'                => 'PHP-FFMpeg API',
    'build_dir'            => __DIR__.'/source/API/API',
    'cache_dir'            => __DIR__.'/source/API/API/cache',
]);
