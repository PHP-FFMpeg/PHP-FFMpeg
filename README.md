#PHP FFmpeg

[![Build Status](https://secure.travis-ci.org/alchemy-fr/PHP-FFmpeg.png?branch=master)](http://travis-ci.org/alchemy-fr/PHP-FFmpeg)

An Object Oriented library to convert video/audio files with FFmpeg / AVConv.

Check another amazing repo : [PHP FFMpeg extras](https://github.com/alchemy-fr/PHP-FFMpeg-Extras), you will find lots of Audio/Video formats there.

## Installation

The recommended way to install PHP-FFMpeg is through [Composer](https://getcomposer.org).

```json
{
    "require": {
        "php-ffmpeg/php-ffmpeg": "~0.3.0"
    }
}
```

## Basic Usage

```php
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
use the static `FFMpeg\FFMpeg::create` :

```php
$ffmpeg = FFMpeg\FFMpeg::create();
```

FFMpeg will autodetect ffmpeg and ffprobe binaries. If you want to give binary
paths explicitely, you can pass an array as configuration. A `Psr\Logger\LoggerInterface`
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

`FFMpeg\FFMpeg` creates media based on file paths. To open a file path, use the
`FFMpeg\FFMpeg::open` method.

```php
$ffmpeg->open('video.mpeg');
```

Two types of media can be resolved : `FFMpeg\Media\Audio` and `FFMpeg\Media\Video`.
A third type, `FFMpeg\Media\Frame`, is available through videos.

#### Video

`FFMpeg\Media\Video` can be transcoded, ie : change codec, isolate audio or
video. Frames can be extracted.

##### Transcoding

You can transcode videos using the `FFMpeg\Media\Video:save` method. You will
pass a `FFMpeg\Format\FormatInterface` for that.

```php
$format = new Format\Video\X264();
$format->on('progress', function ($video, $format, $percentage) {
    echo "$percentage % transcoded";
});

$video->save($format, 'video.avi');
```

Transcoding progress can be monitored in realtime, see Format documentation
below for more informations.

##### Extracting image

You can extract a frame at any timecode using the `FFMpeg\Media\Video::frame`
method.

This code return a `FFMpeg\Media\Frame` instance corresponding to the second 42.
You can pass any `FFMpeg\Coordinate\TimeCode` as argument, see dedicated
documentation below for more information.

```php
$frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(42));
$frame->saveAs('image.jpg');
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
    ->resample($framerate, $gop)
    ->synchronize();
```

###### Resize

Resizes a video to a given size.

```php
$video->filters()->resize($dimension, $mode, $useStandards);
```

The resize filter takes three parameters :

- `$dimension`, an instance of `FFMpeg\Coordinate\Dimension`
- `$mode`, one of the constants `FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_*` constants
- `$useStandards`, a boolean to force the use of the nearest aspect ratio standard.

###### Resample

Resample the video frame rate.

```php
$video->filters()->resample($framerate, $gop);
```

The resample filter takes two parameters :

- `$framerate`, an instance of `FFMpeg\Coordinate\Framerate`
- `$gop`, a [GOP](https://wikipedia.org/wiki/Group_of_pictures) value (integer)

###### Synchronize

Synchronizes audio and video.

Some containers may use a delay that results in desynchronized outputs. This
filters solves this issue.

```php
$video->filters()->synchronize();
```

#### Audio

`FFMpeg\Media\Audio` can be transcoded, ie : change codec, isolate audio or
video. Frames can be extracted.

##### Transcoding

You can transcode audios using the `FFMpeg\Media\Audio:save` method. You will
pass a `FFMpeg\Format\FormatInterface` for that.

```php
$format = new Format\Audio\Flac();
$format->on('progress', function ($$audio, $format, $percentage) {
    echo "$percentage % transcoded";
});

$audio->save($format, 'track.flac');
```

Transcoding progress can be monitored in realtime, see Format documentation
below for more informations.

##### Filters

You can apply filters on `FFMpeg\Media\Audio` with the `FFMpeg\Media\Audio::addFilter`
method. It only accepts audio filters.

You can build your own filters and some are bundled in PHP-FFMpeg - they are
accessible through the `FFMpeg\Media\Audio::filters` method.

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

#### Formats

A format implements `FFMpeg\Format\FormatInterface`. To save to a video file,
use `FFMpeg\Format\VideoInterface`, and `FFMpeg\Format\AudioInterface` for
audio files.

Format can also extends `FFMpeg\Format\ProgressableInterface` to get realtime
informations about the transcoding.

Predefined formats already provide progress informations as events.

```php
$format = new Format\Video\X264();
$format->on('progress', function ($video, $format, $percentage) {
    echo "$percentage % transcoded";
});

$video->save($format, 'video.avi');
```

The callback provided for the event can be any callable.

#### Coordinates

FFMpeg use many units for time and space coordinates.

- `FFMpeg\Coordinate\AspectRatio` represents an aspect ratio.
- `FFMpeg\Coordinate\Dimension` represent a dimension.
- `FFMpeg\Coordinate\FrameRate` represent a framerate.
- `FFMpeg\Coordinate\Point` represent a point.
- `FFMpeg\Coordinate\TimeCode` represent a timecode.


##Using with Silex Microframework

Service provider is easy to set up :

```php
$app = new Silex\Application();
$app->register(new FFMpeg\FFMpegServiceProvider());

$video = $app['ffmpeg']->open('video.mpeg');
```

Available options are as follow :

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

## API Browser

Browse the [API](http://readthedocs.org/docs/ffmpeg-php/en/latest/_static/API/)

## License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).




