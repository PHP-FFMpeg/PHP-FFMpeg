# PHP FFmpeg

[![Build Status](https://secure.travis-ci.org/PHP-FFMpeg/PHP-FFMpeg.png?branch=master)](http://travis-ci.org/PHP-FFMpeg/PHP-FFMpeg)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/607f3111-e2d7-44e8-8bcc-54dd64521983/big.png)](https://insight.sensiolabs.com/projects/607f3111-e2d7-44e8-8bcc-54dd64521983)

An Object Oriented library to convert video/audio files with FFmpeg / AVConv.

Check another amazing repo: [PHP FFMpeg extras](https://github.com/alchemy-fr/PHP-FFMpeg-Extras), you will find lots of Audio/Video formats there.

## Your attention please

### How this library works:

This library requires a working FFMpeg install. You will need both FFMpeg and FFProbe binaries to use it.
Be sure that these binaries can be located with system PATH to get the benefit of the binary detection,
otherwise you should have to explicitly give the binaries path on load.

For Windows users : Please find the binaries at http://ffmpeg.zeranoe.com/builds/.

### Known issues:

- Using rotate and resize will produce a corrupted output when using
[libav](http://libav.org/) 0.8. The bug is fixed in version 9. This bug does not
appear in latest ffmpeg version.

## Installation

The recommended way to install PHP-FFMpeg is through [Composer](https://getcomposer.org).

```bash
$ composer require php-ffmpeg/php-ffmpeg
```

## Basic Usage

```php

require 'vendor/autoload.php';

$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open('video.mpg');
$video
    ->filters()
    ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
    ->synchronize();
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
    ->save('frame.jpg');
$video
    ->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4')
    ->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
    ->save(new FFMpeg\Format\Video\WebM(), 'export-webm.webm');
```

## Documentation

This documentation is an introduction to discover the API. It's recommended
to browse the source code as it is self-documented.

### FFMpeg

`FFMpeg\FFMpeg` is the main object to use to manipulate medias. To build it,
use the static `FFMpeg\FFMpeg::create`:

```php
$ffmpeg = FFMpeg\FFMpeg::create();
```

FFMpeg will autodetect ffmpeg and ffprobe binaries. If you want to give binary
paths explicitly, you can pass an array as configuration. A `Psr\Logger\LoggerInterface`
can also be passed to log binary executions.

```php
$ffmpeg = FFMpeg\FFMpeg::create(array(
    'ffmpeg.binaries'  => '/opt/local/ffmpeg/bin/ffmpeg',
    'ffprobe.binaries' => '/opt/local/ffmpeg/bin/ffprobe',
    'timeout'          => 3600, // The timeout for the underlying process
    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
), $logger);
```

### Manipulate media

`FFMpeg\FFMpeg` creates media based on URIs. URIs could be either a pointer to a
local filesystem resource, an HTTP resource or any resource supported by FFmpeg.

**Note**: To list all supported resource type of your FFmpeg build, use the
`-protocols` command:

```
ffmpeg -protocols
```

To open a resource, use the `FFMpeg\FFMpeg::open` method.

```php
$ffmpeg->open('video.mpeg');
```

Two types of media can be resolved: `FFMpeg\Media\Audio` and `FFMpeg\Media\Video`.
A third type, `FFMpeg\Media\Frame`, is available through videos.

### Video

`FFMpeg\Media\Video` can be transcoded, ie: change codec, isolate audio or
video. Frames can be extracted.

##### Transcoding

You can transcode videos using the `FFMpeg\Media\Video:save` method. You will
pass a `FFMpeg\Format\FormatInterface` for that.

Please note that audio and video bitrate are set on the format.

```php
$format = new FFMpeg\Format\Video\X264();
$format->on('progress', function ($video, $format, $percentage) {
    echo "$percentage % transcoded";
});

$format
    ->setKiloBitrate(1000)
    ->setAudioChannels(2)
    ->setAudioKiloBitrate(256);

$video->save($format, 'video.avi');
```

Transcoding progress can be monitored in realtime, see Format documentation
below for more informations.

##### Extracting image

You can extract a frame at any timecode using the `FFMpeg\Media\Video::frame`
method.

This code returns a `FFMpeg\Media\Frame` instance corresponding to the second 42.
You can pass any `FFMpeg\Coordinate\TimeCode` as argument, see dedicated
documentation below for more information.

```php
$frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(42));
$frame->save('image.jpg');
```

If you want to extract multiple images from your video, you can use the following filter:

```php
$video
    ->filters()
    ->extractMultipleFrames(FFMpeg\Filters\Video\ExtractMultipleFramesFilter::FRAMERATE_EVERY_10SEC, '/path/to/destination/folder/')
    ->synchronize();

$video
    ->save(new FFMpeg\Format\Video\X264(), '/path/to/new/file');
```

##### Generate a waveform

You can generate a waveform of an audio file using the `FFMpeg\Media\Audio::waveform`
method.

This code returns a `FFMpeg\Media\Waveform` instance.
You can optionally pass dimensions as the first two arguments and an array of hex string colors for ffmpeg to use for the waveform, see dedicated
documentation below for more information.

The ouput file MUST use the PNG extension.

```php
$waveform = $audio->waveform(640, 120, array('#00FF00'));
$waveform->save('waveform.png');
```

If you want to get a waveform from a video, convert it in an audio file first.

```php
// Open your video file
$video = $ffmpeg->open( 'video.mp4' );

// Set an audio format
$audio_format = new FFMpeg\Format\Audio\Mp3();

// Extract the audio into a new file as mp3
$video->save($audio_format, 'audio.mp3');

// Set the audio file
$audio = $ffmpeg->open( 'audio.mp3' );

// Create the waveform
$waveform = $audio->waveform();
$waveform->save( 'waveform.png' );
```

##### Filters

You can apply filters on `FFMpeg\Media\Video` with the `FFMpeg\Media\Video::addFilter`
method. Video accepts Audio and Video filters.

You can build your own filters and some are bundled in PHP-FFMpeg - they are
accessible through the `FFMpeg\Media\Video::filters` method.

Filters are chainable

```php
$video
    ->filters()
    ->resize($dimension, $mode, $useStandards)
    ->framerate($framerate, $gop)
    ->synchronize();
```

###### Rotate

Rotates a video to a given angle.

```php
$video->filters()->rotate($angle);
```

The `$angle` parameter must be one of the following constants :

- `FFMpeg\Filters\Video\RotateFilter::ROTATE_90`: 90° clockwise
- `FFMpeg\Filters\Video\RotateFilter::ROTATE_180`: 180°
- `FFMpeg\Filters\Video\RotateFilter::ROTATE_270`: 90° counterclockwise

###### Resize

Resizes a video to a given size.

```php
$video->filters()->resize($dimension, $mode, $useStandards);
```

The resize filter takes three parameters:

- `$dimension`, an instance of `FFMpeg\Coordinate\Dimension`
- `$mode`, one of the constants `FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_*` constants
- `$useStandards`, a boolean to force the use of the nearest aspect ratio standard.

If you want a video in a non-standard ratio, you can use the padding filter to resize your video in the desired size, and wrap it into black bars.

```php
$video->filters()->pad($dimension);
```

The pad filter takes one parameter:

- `$dimension`, an instance of `FFMpeg\Coordinate\Dimension`

Don't forget to save it afterwards.

```php
$video->save(new FFMpeg\Format\Video\X264(), $new_file);
```

###### Watermark

Watermark a video with a given image.

```php
$video
    ->filters()
    ->watermark($watermarkPath, array(
        'position' => 'relative',
        'bottom' => 50,
        'right' => 50,
    ));
```

The watermark filter takes two parameters:

`$watermarkPath`, the path to your watermark file.
`$coordinates`, an array defining how you want your watermark positioned. You can use relative positioning as demonstrated above or absolute as such:

```php
$video
    ->filters()
    ->watermark($watermarkPath, array(
        'position' => 'absolute',
        'x' => 1180,
        'y' => 620,
    ));
```

###### Framerate

Changes the frame rate of the video.

```php
$video->filters()->framerate($framerate, $gop);
```

The framerate filter takes two parameters:

- `$framerate`, an instance of `FFMpeg\Coordinate\Framerate`
- `$gop`, a [GOP](https://wikipedia.org/wiki/Group_of_pictures) value (integer)

###### Synchronize

Synchronizes audio and video.

Some containers may use a delay that results in desynchronized outputs. This
filters solves this issue.

```php
$video->filters()->synchronize();
```

###### Clip

Cuts the video at a desired point.

```php
$video->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(30), FFMpeg\Coordinate\TimeCode::fromSeconds(15));
```

The clip filter takes two parameters:

- `$start`, an instance of `FFMpeg\Coordinate\TimeCode`, specifies the start point of the clip
- `$duration`, optional, an instance of `FFMpeg\Coordinate\TimeCode`, specifies the duration of the clip

###### Crop

Crops the video based on a width and height(a `Point`)

```php
$video->filters()->crop(new FFMpeg\Coordinate\Point("t*100", 0, true), new FFMpeg\Coordinate\Dimension(200, 600));
```

It takes two parameters:
- `$point`, an instance of `FFMpeg\Coordinate\Point`, specifies the point to crop
- `$dimension`, an instance of `FFMpeg\Coordinate\Dimension`, specifies the dimension of the output video

### Audio

`FFMpeg\Media\Audio` can be transcoded too, ie: change codec, isolate audio or
video. Frames can be extracted.

##### Transcoding

You can transcode audios using the `FFMpeg\Media\Audio:save` method. You will
pass a `FFMpeg\Format\FormatInterface` for that.

Please note that audio kilobitrate is set on the audio format.

```php
$ffmpeg = FFMpeg\FFMpeg::create();
$audio = $ffmpeg->open('track.mp3');

$format = new FFMpeg\Format\Audio\Flac();
$format->on('progress', function ($audio, $format, $percentage) {
    echo "$percentage % transcoded";
});

$format
    ->setAudioChannels(2)
    ->setAudioKiloBitrate(256);

$audio->save($format, 'track.flac');
```

Transcoding progress can be monitored in realtime, see Format documentation
below for more informations.

##### Filters

You can apply filters on `FFMpeg\Media\Audio` with the `FFMpeg\Media\Audio::addFilter`
method. It only accepts audio filters.

You can build your own filters and some are bundled in PHP-FFMpeg - they are
accessible through the `FFMpeg\Media\Audio::filters` method.

##### Clipping
Cuts the audio at a desired point.

```php
$audio->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(30), FFMpeg\Coordinate\TimeCode::fromSeconds(15));
```


###### Metadata

Add metadata to audio files. Just pass an array of key=value pairs of all
metadata you would like to add. If no arguments are passed into the filter
all metadata will be removed from input file. Currently supported data is
title, artist, album, artist, composer, track, year, description, artwork

```php
$audio->filters()->addMetadata(["title" => "Some Title", "track" => 1]);

//remove all metadata and video streams from audio file
$audio->filters()->addMetadata();
```

Add artwork to the audio file
```php
$audio->filters()->addMetadata(["artwork" => "/path/to/image/file.jpg"]);
```
NOTE: at present ffmpeg (version 3.2.2) only supports artwork output for .mp3
files

###### Resample

Resamples an audio file.

```php
$audio->filters()->resample($rate);
```

The resample filter takes two parameters :

- `$rate`, a valid audio sample rate value (integer)

#### Frame

A frame is a image at a timecode of a video ; see documentation above about
frame extraction.

You can save frames using the `FFMpeg\Media\Frame::save` method.

```php
$frame->save('target.jpg');
```

This method has a second optional boolean parameter. Set it to true to get
accurate images ; it takes more time to execute.

#### Gif

A gif is an animated image extracted from a sequence of the video.

You can save gif files using the `FFMpeg\Media\Gif::save` method.

```php
$video = $ffmpeg->open( '/path/to/video' );
$video
    ->gif(FFMpeg\Coordinate\TimeCode::fromSeconds(2), new FFMpeg\Coordinate\Dimension(640, 480), 3)
    ->save($new_file);
```

This method has a third optional boolean parameter, which is the duration of the animation.
If you don't set it, you will get a fixed gif image.

#### Concatenation

This feature allows you to generate one audio or video file, based on multiple sources.

There are two ways to concatenate videos, depending on the codecs of the sources.
If your sources have all been encoded with the same codec, you will want to use the `FFMpeg\Media\Concatenate::saveFromSameCodecs` which has way better performances.
If your sources have been encoded with different codecs, you will want to use the `FFMpeg\Media\Concatenate::saveFromDifferentCodecs`.

The first function will use the initial codec as the one for the generated file.
With the second function, you will be able to choose which codec you want for the generated file.

You also need to pay attention to the fact that, when using the saveFromDifferentCodecs method,
your files MUST have video and audio streams.

In both cases, you will have to provide an array of files.

To concatenate videos encoded with the same codec, do as follow:

```php
// In order to instantiate the video object, you HAVE TO pass a path to a valid video file.
// We recommand that you put there the path of any of the video you want to use in this concatenation.
$video = $ffmpeg->open( '/path/to/video' );
$video
    ->concat(array('/path/to/video1', '/path/to/video2'))
    ->saveFromSameCodecs('/path/to/new_file', TRUE);
```

The boolean parameter of the save function allows you to use the copy parameter which accelerates drastically the generation of the encoded file.

To concatenate videos encoded with the same codec, do as follow:

```php
// In order to instantiate the video object, you HAVE TO pass a path to a valid video file.
// We recommand that you put there the path of any of the video you want to use in this concatenation.
$video = $ffmpeg->open( '/path/to/video' );

$format = new FFMpeg\Format\Video\X264();
$format->setAudioCodec("libmp3lame");

$video
    ->concat(array('/path/to/video1', '/path/to/video2'))
    ->saveFromDifferentCodecs($format, '/path/to/new_file');
```

More details about concatenation in FFMPEG can be found [here](https://trac.ffmpeg.org/wiki/Concatenate), [here](https://ffmpeg.org/ffmpeg-formats.html#concat-1) and [here](https://ffmpeg.org/ffmpeg.html#Stream-copy).

#### Formats

A format implements `FFMpeg\Format\FormatInterface`. To save to a video file,
use `FFMpeg\Format\VideoInterface`, and `FFMpeg\Format\AudioInterface` for
audio files.

Format can also extends `FFMpeg\Format\ProgressableInterface` to get realtime
informations about the transcoding.

Predefined formats already provide progress informations as events.

```php
$format = new FFMpeg\Format\Video\X264();
$format->on('progress', function ($video, $format, $percentage) {
    echo "$percentage % transcoded";
});

$video->save($format, 'video.avi');
```

The callback provided for the event can be any callable.

##### Add additional parameters

You can add additional parameters to your encoding requests based on your video format.

The argument of the setAdditionalParameters method is an array.

```php
$format = new FFMpeg\Format\Video\X264();
$format->setAdditionalParameters(array('foo', 'bar'));
$video->save($format, 'video.avi');
```

##### Create your own format

The easiest way to create a format is to extend the abstract
`FFMpeg\Format\Video\DefaultVideo` and `FFMpeg\Format\Audio\DefaultAudio`.
and implement the following methods.

```php
class CustomWMVFormat extends FFMpeg\Format\Video\DefaultVideo
{
    public function __construct($audioCodec = 'wmav2', $videoCodec = 'wmv2')
    {
        $this
            ->setAudioCodec($audioCodec)
            ->setVideoCodec($videoCodec);
    }

    public function supportBFrames()
    {
        return false;
    }

    public function getAvailableAudioCodecs()
    {
        return array('wmav2');
    }

    public function getAvailableVideoCodecs()
    {
        return array('wmv2');
    }
}
```

#### Coordinates

FFMpeg use many units for time and space coordinates.

- `FFMpeg\Coordinate\AspectRatio` represents an aspect ratio.
- `FFMpeg\Coordinate\Dimension` represent a dimension.
- `FFMpeg\Coordinate\FrameRate` represent a framerate.
- `FFMpeg\Coordinate\Point` represent a point. (Supports dynamic points since v0.10.0)
- `FFMpeg\Coordinate\TimeCode` represent a timecode.

### FFProbe

`FFMpeg\FFProbe` is used internally by `FFMpeg\FFMpeg` to probe medias. You can
also use it to extract media metadata.

```php
$ffprobe = FFMpeg\FFProbe::create();
$ffprobe
    ->streams('/path/to/video/mp4') // extracts streams informations
    ->videos()                      // filters video streams
    ->first()                       // returns the first video stream
    ->get('codec_name');            // returns the codec_name property
```

```php
$ffprobe = FFMpeg\FFProbe::create();
$ffprobe
    ->format('/path/to/video/mp4') // extracts file informations
    ->get('duration');             // returns the duration property
```

### Validating media files

(since 0.10.0)
You can validate media files using PHP-FFMpeg's FFProbe wrapper.

```php
$ffprobe = FFMpeg\FFProbe::create();
$ffprobe->isValid('/path/to/file/to/check'); // returns bool
```

## Using with Silex Microframework

Service provider is easy to set up:

```php
$app = new Silex\Application();
$app->register(new FFMpeg\FFMpegServiceProvider());

$video = $app['ffmpeg']->open('video.mpeg');
```

Available options are as follow:

```php
$app->register(new FFMpeg\FFMpegServiceProvider(), array(
    'ffmpeg.configuration' => array(
        'ffmpeg.threads'   => 4,
        'ffmpeg.timeout'   => 300,
        'ffmpeg.binaries'  => '/opt/local/ffmpeg/bin/ffmpeg',
        'ffprobe.timeout'  => 30,
        'ffprobe.binaries' => '/opt/local/ffmpeg/bin/ffprobe',
    ),
    'ffmpeg.logger' => $logger,
));
```

## License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).
