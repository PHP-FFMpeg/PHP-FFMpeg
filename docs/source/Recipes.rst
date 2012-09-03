Recipes
=======

Initializing FFMpeg
-------------------

In the following examples, we assume we work in an environnment where
FFMpeg has been initialized to ``$ffmpeg``; there are two ways to
initialize the environment (see below).

PHP-FFMpeg supports both ``avconv`` and legacy ``ffmpeg``. If both are installed
on your system, ``avconv`` will be loaded in first priority. Please read the
dedicated chapter below if you want to load ``FFMpeg``.

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

.. note:: FFMpeg and FFProbe both requires a logger for giving feedback about
    what's happening. By passing a NullHandler to the logger, you will disable
    the log system.


Use custom binary
^^^^^^^^^^^^^^^^^

You can also initialize with a custom path to the binary :

.. code-block:: php

    <?php
    $ffmpeg = new FFMpeg('/usr/local/src/ffmpeg/bin/ffmpeg', $logger);

Working with Silex Microframework
---------------------------------

If you're using `Silex MicroFramework <http://silex.sensiolabs.org/>`_, you can
use FFMpeg-PHP with the bundled Service Provider :

.. code-block:: php

    <?php
    use Silex\Application;
    use FFMpeg\FFMpegServiceProvider;

    $app = new Application();
    $app->register(new FFMpegServiceProvider());

    // Instance of FFMpeg\FFMpeg
    $app['ffmpeg.ffmpeg'];

    // Instance of FFMpeg\FFProbe
    $app['ffmpeg.ffprobe'];


Service Provider options
^^^^^^^^^^^^^^^^^^^^^^^^

By default, `FFMpegServiceProvider` will look for monolog service to log
messages. You can customize FFMpeg logger with the 'ffmpeg.logger' option.

You can also customize the number of threads to open and the path to FFMpeg and
FFProbe binaries :

.. code-block:: php

    <?php
    use Silex\Application;
    use FFMpeg\FFMpegServiceProvider;
    use Monolog\Logger;
    use Monolog\Handler\NullHandler;

    $app = new Application();

    $logger = new Logger('FFMpeg');
    $logger->pushHandler(new NullHandler());

    $options = array(
        'ffmpeg.ffmpeg.binary'  => '/path/to/custom/ffmpeg/binary',
        'ffmpeg.ffprobe.binary' => '/path/to/custom/ffprobe/binary',
        'ffmpeg.threads'        => 8,       # number of threads to open
        'ffmpeg.logger'         => $logger, # custom logger service
    );

    $app->register(new FFMpegServiceProvider(), $options);


Formats
-------

PHP-FFMpeg provides a set of predefined audio and video formats. These formats
are usefull, but you'll probably need to define your own format with their own
resize rules, etc...

This section describe how to use media formats, and how to define them.

.. note:: Defining a format is just about implementing interfaces.

.. _video-reference:

Video
^^^^^

This section describes video processing and Interfaces for building video
formats. As Video is an extension of audio, all these features can be combined
with audio features (see :ref:`dedicated audio section<audio-reference>`).

Simple transcoding
++++++++++++++++++

To transcode a video, you have to pass the target format to FFMpeg.

When you define a format which implements the
:ref:`Resizable <resizable-reference>` interface.
You must set FFprobe (see :ref:`FF-probe<ffprobe-reference>`)
for probing the media and found its height and size.

The following example initialize a Ogg format and encodes a `Video.mpeg` to a
target file `file.ogv` :

.. code-block:: php

    <?php
    use FFMpeg\FFProbe;
    use FFMpeg\Format\Video\Ogg;

    $ffprobe = FFProbe::load($logger);

    $ffmpeg->setProber($ffprobe);

    $oggFormat = new Ogg();

    $ffmpeg->open('Video.mpeg')
        ->encode($oggFormat, 'file.ogv')
        ->close();

.. note:: ``FFmpeg`` methods always return the object itself so you can chain
    multiple methods.

HTML5
+++++

PHP-FFMpeg provides three video formats out of the box : HTML5 video formats

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

This section presents usage for the different interfaces. You can combine
them for your own formats.

.. _resizable-reference:

Resizable
.........

This interface provide an easy way to resize a video.
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

.. _resamplable-reference:

Resamplable
...........

This interface provides video resampling. The example below resample the video
at 15 frame per second with a I-frame every 30 image (see
`GOP on wikipedia <https://wikipedia.org/wiki/Group_of_pictures>`_) and supports
B-frames (see `B-frames on wikipedia <https://wikipedia.org/wiki/Video_compression_picture_types>`_)

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

        public function supportBFrames()
        {
            return true;
        }
    }

    $format = new MyFormat();

    $ffmpeg->open('Video.mpeg')
        ->encode($format, 'file.mp4')
        ->close();

Interactive
...........

This interface provides a method to list available codecs for the format.
The example below provides a format object listing available video-codecs for
video supported in flash player.

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

.. _audio-reference:

Audio
^^^^^

This section describes audio processing and Interfaces for building video
formats. As Video is an extension of audio, all these features can be combined
with video features (see :ref:`dedicated video section<video-reference>`).

Simple transcoding
++++++++++++++++++

To transcode audio file or extract an audio soundtrack from a video, you have to
pass the target format to FFMpeg.

The following example initialize a Mp3 format and transcode the file `tune.wav`
to `tune.mp3` :

.. code-block:: php

    <?php
    use FFMpeg\Format\Audio\Mp3;

    $mp3Format = new Mp3();

    $ffmpeg->open('tune.wav')
        ->encode($mp3Format, 'tune.mp3')
        ->close();

Extract soundtrack from movie
+++++++++++++++++++++++++++++

The following example initialize a Flac format and extract the audio track from
`Video.mpeg` to a target file `soudtrack.flac` :

.. code-block:: php

    <?php
    use FFMpeg\Format\Audio\Flac;

    $flacFormat = new Flac();

    $ffmpeg->open('Video.mpeg')
        ->encode($flacFormat, 'soundtrack.flac')
        ->close();

.. note:: You must ensure that FFmpeg support the format you request, otherwise
    a FFMpeg\Exception\RuntimeException will be thrown.

Create your own media type
++++++++++++++++++++++++++

PHP-FFMpeg provides ``FFMpeg\Format\Audio``, as base interface for creating an
Audio format. To define a target format, all you need to do is implement this
Interface.

This example transcodes the mp3 track to a 128kb mp3 :

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Audio;

    class MyFormat implements Audio
    {
        public function getKiloBitrate()
        {
            return 128;
        }

        public function getExtraParams()
        {
            return '';
        }
    }

    $format = new MyFormat();

    $ffmpeg->open('song.mp3')
        ->encode($format, 'song-128.mp3')
        ->close();

PHP-FFmpeg brings more interfaces for your audio formats :

 - ``FFMpeg\Format\Audio\Resamplable``
 - ``FFMpeg\Format\Audio\Transcodable``
 - ``FFMpeg\Format\Audio\Interactive``

.. note:: You can combine these features in one video format.

Advanced media type
+++++++++++++++++++

This section presents usage for the different audio interfaces. You can combine
them for your own formats.

Resamplable
...........

This interface provides video resampling. The example below resample the video
at 15 frame per second with a I-frame every 30 image (see
`GOP on wikipedia <https://wikipedia.org/wiki/Group_of_pictures>`_).

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Audio\Resamplable;

    class MyFormat implements Resamplable
    {
        public function getAudioSampleRate();
        {
            return 44100;
        }

    }

    $format = new MyFormat();

    $ffmpeg->open('song.mp3')
        ->encode($format, 'song-44100.mp3')
        ->close();


Interactive
...........

This interface provides a method to list available codecs for the format.
The example below provides a format object listing available audio-codecs for
a portable player.

.. code-block:: php

    <?php
    namespace Foo\Bar;

    use FFMpeg\Format\Audio\Interactive;

    class MyFormat implements Interactive
    {

        public function getAudioCodec()
        {
            return 'libvorbis';
        }

        public function getAvailableVideoCodecs()
        {
            return array('libvorbis', 'libmp3lame', 'libflac');
        }
    }


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

.. _ffprobe-reference:

FFProbe recipes
---------------

FFProbe / AvProbe is a usefull tool for probing media files. PHP-FFMpeg
implementation is currenly light.

Load FFProbe
^^^^^^^^^^^^

As FFMpeg, you can load FFProbe two ways ; either with the binary detector or
specifying the binary you want to use

.. code-block:: php

    <?php
    use Monolog\Logger;
    use Monolog\Handler\NullHandler;
    use FFMpeg\FFProbe;

    // Create a logger
    $logger = new Logger('MyLogger');
    $logger->pushHandler(new NullHandler());

    // ------------------------------------------------------------------------
    // Load with binary detection

    // You have to pass a Monolog logger
    // This logger provides some usefull infos about what's happening
    $ffprobe = FFProbe::load($logger);


    // ------------------------------------------------------------------------
    // Or load manually

    $ffprobe = new FFProbe('/usr/local/src/ffmpeg/bin/ffprobe', $logger);


Probe streams
^^^^^^^^^^^^^

Probe streams returns the output of ``avprobe -show_streams`` as  a json
object.

.. code-block:: php

    <?php
    echo $ffprobe->probeStreams('Video.ogv');

will output something like

.. code-block:: json

    [
        {
            "index": 0,
            "codec_name": "theora",
            "codec_long_name": "Theora",
            "codec_type": "video",
            "codec_time_base": "1/15",
            "codec_tag_string": "[0][0][0][0]",
            "codec_tag": "0x0000",
            "width": 400,
            "height": 304,
            "has_b_frames": 0,
            "pix_fmt": "yuv420p",
            "level": "-99",
            "r_frame_rate": "15/1",
            "avg_frame_rate": "15/1",
            "time_base": "1/15",
            "start_time": "0.000000",
            "duration": "29.533333"
        },
        {
            "index": 1,
            "codec_name": "vorbis",
            "codec_long_name": "Vorbis",
            "codec_type": "audio",
            "codec_time_base": "1/44100",
            "codec_tag_string": "[0][0][0][0]",
            "codec_tag": "0x0000",
            "sample_rate": "44100.000000",
            "channels": 2,
            "bits_per_sample": 0,
            "r_frame_rate": "0/0",
            "avg_frame_rate": "0/0",
            "time_base": "1/44100",
            "start_time": "0.000000",
            "duration": "29.489342",
            "TAG:TITLE": "Halo",
            "TAG:LICENSE": "http://creativecommons.org/licenses/publicdomain/",
            "TAG:LOCATION": "http://www.archive.org/details/ctvc"
        }
    ]


Probe formats
^^^^^^^^^^^^^

Probe format returns the output of ``avprobe -show_format`` as  a json
object.

.. code-block:: php

    <?php
    echo $ffprobe->probeFormat('Video.ogv');

will output something like

.. code-block:: json

    {
        "filename": "Video.ogv",
        "nb_streams": 2,
        "format_name": "ogg",
        "format_long_name": "Ogg",
        "start_time": "0.000000",
        "duration": "29.533333",
        "size": "1786693.000000",
        "bit_rate": "483980.000000"
    }
