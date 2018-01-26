<?php

opcache_reset();

require_once './vendor/autoload.php';

$ffprobe = FFMpeg\FFProbe::create();

($ffprobe->getCodecTester()->getAvailableCodecs());