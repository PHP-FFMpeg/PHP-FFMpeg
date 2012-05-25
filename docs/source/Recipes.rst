PHP FFMpeg Recipes
==================

Initializing FFMpeg
-------------------

In the examples  we will show you, we assume we work in an environnment where
FFMpeg has been initialized to ``$ffmpeg`` var ; there are two ways to
initialize the environment :

Autoloading FFMpeg
^^^^^^^^^^^^^^^^^^

The easiest way to initialize ``FMpeg`` it is to call the loader ; this will
look in your PATH environment variable to find ffmpeg/avconv binary :

.. code-block:: php

    <?php
    use Monolog\Logger;
    use Monolog\Handler\NullHandler;
    use FFMpeg\FFMpeg;

    // Create a logger
    $logger = new Logger('MyLogger');
    $logger->pushHandler(new NullHandler());

    // You have to pass a Monolog logger
    // This logger provides some usefull infos about what's happening
    $ffmpeg = FFMpeg::load($logger);

.. note:: FFMpeg and FFProbe both requires a logger with gives ffedback about
    what's happening. By passing a NullHandler to the logger, you disable the
    logging system.


Use custom binary
^^^^^^^^^^^^^^^^^

You can also initialize with a custom path to the binary :

.. code-block:: php

    <?php
    $ffmpeg = new FFMpeg::load('/usr/local/src/ffmpeg/bin/ffmpeg', $logger);


Defining formats
----------------

Encode a video
^^^^^^^^^^^^^^

To encode a video, you have to define the target format. A format is an object
that implements ``FFMpeg\Format\Video``

The following example initialize a Ogg format and encodes a `Video.mpeg` to a
target file `file.ogv` :

.. code-block:: php

    <?php
    use FFMpeg\Format\Video\Ogg;

    $oggFormat = new Ogg();

    $ffmpeg->open('Video.mpeg')
        ->encode($oggFormat, 'file.ogv')
        ->close();

Create HTML5 videos
^^^^^^^^^^^^^^^^^^^

PHP-FFMpeg provides three video format out of the box : HTML5 video formats.
These formats are available as these objects :

 - ``FFMpeg\Format\Video\WebM``
 - ``FFMpeg\Format\Video\X264``
 - ``FFMpeg\Format\Video\Ogg``

.. code-block:: php

    <?php
    use FFMpeg\Format\Video;

    $webMFormat = new Video\WebM();
    $webMFormat->setDimensions(320, 240)
        ->setFrameRate(15)
        ->setGopSize(25);
    $x264Format = new Video\X264();
    $x264Format->setDimensions(320, 240)
        ->setFrameRate(15)
        ->setGopSize(25);

    $oggFormat = new Video\Ogg();
    $oggFormat->setDimensions(320, 240)
        ->setFrameRate(15)
        ->setGopSize(25);

    $ffmpeg->open('Video.mpeg')
        ->encode($webMFormat, 'file.webm')
        ->encode($x264Format, 'file.mp4')
        ->encode($oggFormat, 'file.ogv')
        ->close();

.. note: Use PHP-MP4Box to make it compatible with pseudo stream !

Create your own media type
^^^^^^^^^^^^^^^^^^^^^^^^^^


FFProbe recipes
---------------


create HTML5 videos
^^^^^^^^^^^^^^^^^^^

