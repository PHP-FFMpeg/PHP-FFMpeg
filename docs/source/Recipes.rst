Recipes
=======

Initializing FFMpeg
-------------------

In the examples  we will show you, we assume we work in an environnment where
FFMpeg has been initialized to ``$ffmpeg`` var ; there are two ways to
initialize the environment.

PHP-FFMpeg supports both ``avconv`` and legacy ``ffmpeg``. If both are installed 
on your system, ``avconv`` will be loaded in priority. Please read the dedicated 
chapter below if you want to load ``FFMpeg``.

Load FFMpeg automatically
^^^^^^^^^^^^^^^^^^^^^^^^^

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

.. note:: FFMpeg and FFProbe both requires a logger with gives feedback about
    what's happening. By passing a NullHandler to the logger, you will disable 
    the log system.


Use custom binary
^^^^^^^^^^^^^^^^^

You can also initialize with a custom path to the binary :

.. code-block:: php

    <?php
    $ffmpeg = new FFMpeg('/usr/local/src/ffmpeg/bin/ffmpeg', $logger);

Formats
-------

PHP-FFMpeg provibes a set of predefined audio and video formats. These format
are usefulll, but you'll probably need to define your own format with their own
resize rules, etc...

This section describe hox to use media formats, and how to define them. 

.. note:: Defining a format is just about implementing interfaces.

Video
^^^^^

This section describes video processing and Interfaces for building video 
formats.

Simple transcoding
++++++++++++++++++

To transcode a video, you have to pass the target format to FFMpeg.

The following example initialize a Ogg format and encodes a `Video.mpeg` to a
target file `file.ogv` :

.. code-block:: php

    <?php
    use FFMpeg\Format\Video\Ogg;

    $oggFormat = new Ogg();

    $ffmpeg->open('Video.mpeg')
        ->encode($oggFormat, 'file.ogv')
        ->close();

.. note:: ``FFmpeg`` methods always return the object itself so you can chain
    multiple methods.

HTML5
+++++

PHP-FFMpeg provides three video format out of the box : HTML5 video formats 

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

.. note:: All formats provided by PHP-FFMpeg extends DefaultVideo, have a look
    at the API doc for more information on its behavior.

.. note:: Use PHP-MP4Box to make it compatible with pseudo stream !

Create your own media type
++++++++++++++++++++++++++

PHP-FFMpeg provides ``FFMpeg\Format\Video``, as base interface for creating a
Video format. To define a target format, all you need to do is implement this
Interface.

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Video;

    class MyFormat implements Video
    {
        public function getKiloBitrate()
        {
            return 1500;
        }

        public function getExtraParams()
        {
            return '';
        }
    }

    $format = new MyFormat();

    $ffmpeg->open('Video.mpeg')
        ->encode($format, 'file.mp4')
        ->close();

PHP-FFmpeg brings more interfaces for your video formats : 
 - ``FFMpeg\Format\Video\Resamplable``
 - ``FFMpeg\Format\Video\Resizable``
 - ``FFMpeg\Format\Video\Transcodable`` 
 - ``FFMpeg\Format\Video\Interactive``

.. note:: You can combine these features in one video format.

Advanced media type
+++++++++++++++++++

This section present basic usage of the different interfaces. You can combine 
them in your own format.

Resizable
.........

This interface provide an easy way to resize a video
The example below resizes a video by half.

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Dimension;
    use FFMpeg\Format\Video\Resizable;

    class MyFormat implements Resizable
    {
        public function getComputedDimensions($originalWidth, $originalHeight)
        {
            return new Dimension(round($originalWidth / 2), round($originalHeight / 2));
        }

    }

    $format = new MyFormat();

    $ffmpeg->open('Video.mpeg')
        ->encode($format, 'file.mp4')
        ->close();


Resamplable
...........

This interface provide an easy way to resample a video
The example below resample the video at 15 frame per second with a keyframe 
every 30 image.

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Video\Resamplable;

    class MyFormat implements Resamplable
    {
        public function getFrameRate()
        {
            return 15;
        }

        public function getGOPSize()
        {
            return 30;
        }

    }

    $format = new MyFormat();

    $ffmpeg->open('Video.mpeg')
        ->encode($format, 'file.mp4')
        ->close();

Interactive
...........

This interface provide a method to list available codec for the format
The example below provide a format object listing available videocodec for
flash video.

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Video\Interactive;

    class MyFormat implements Interactive
    {
        
        public function getVideoCodec()
        {
            return 'libx264';
        }

        public function getAvailableVideoCodecs()
        {
            return array('libx264', 'flv');
        }
    }

    $format = new MyFormat();

    $ffmpeg->open('Video.mpeg')
        ->encode($format, 'file.mp4')
        ->close();


Audio
^^^^^

This section describes audio processing and Interfaces for building video 
formats.

Simple transcoding
++++++++++++++++++

Extract soundtrack from movie
+++++++++++++++++++++++++++++

Create your own media type
++++++++++++++++++++++++++

Advanced media type
+++++++++++++++++++

Resamplable
...........

Transcodable
............

Interactive
...........


Custom commandline options
^^^^^^^^^^^^^^^^^^^^^^^^^^

If you need to add custom FFmpeg command line option, use the 
``FFMpeg\Format\Audio::getExtraParams`` method.
As ``Video`` extends ``Audio``, it is also available in any format.

The following example shows a ``getExtraParams`` usage for aac encoding. With the
latest AvConv / FFMPeg version, aac encoding has to be executed with extra command parameters
``-strict experimental``.

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Audio\Transcodable;
    use FFMpeg\Format\Video;

    class MyFormat implements Video, Transcodable
    {
        
        public function getAudioCodec()
        {
            return 'aac';
        }

        public function getKiloBitrate()
        {
            return 128;
        }

        public function getExtraParams()
        {
            return '-strict experimental';
        }
    }

    $format = new MyFormat();

    $ffmpeg->open('Video.mp4')
        ->encode($format, 'output-aac.mp4')
        ->close();

FFProbe recipes
---------------


