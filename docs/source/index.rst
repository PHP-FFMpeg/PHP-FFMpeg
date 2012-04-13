FFmpeg PHP documentation
========================

FFMpeg PHP is an Object Oriented library which allow easy to use file
conversion with ffmpeg.

Basics
------

The following code opens an audio file named "audio.wav" and convert it to a
256kbps mp3.

.. code-block:: php

    <?php

    $source = 'audio.wav';
    $dest = 'audio.flac';

    $ffmpeg = \FFMpeg\FFMpeg::load();

    $ffmpeg->open('audio.wav');

    $mp3Format = new FFMpeg\Format\Audio\Mp3();
    $mp3Format->setKiloBitrate(256);

    $ffmpeg->encode($mp3Format, 'audio.mp3');



