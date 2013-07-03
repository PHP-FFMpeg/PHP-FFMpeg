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

##### Transcoding

##### Filters

#### Frame

##### Filters

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

##### `FFMpeg\Coordinate\AspectRatio`

##### `FFMpeg\Coordinate\Dimension`

##### `FFMpeg\Coordinate\FrameRate`

##### `FFMpeg\Coordinate\Point`

##### `FFMpeg\Coordinate\TimeCode`

##Usage Example

```php
$x264 = new X264();
$x264->setDimensions(320, 240);

$ffmpeg->open('Video.mpeg')
    ->encode($new WebM(), 'file.webm')
    ->encode($x264, 'file.mp4')
    ->encode($new Ogg(), 'file.ogv')
    ->close();
```

##Getting progress information


```php
$progressHelper = new FFMpeg\Helper\AudioProgressHelper(function($percent, $remaining, $rate) {
	echo "Current progress: " . $percent "%\n";
	echo "Remaining time: " . $remaining " seconds\n";
});

$ffmpeg->open('Audio.wav')
	->attachHelper($progressHelper)
    ->encode(new Mp3(), 'file.mp3')
    ->close();
```

##Using with Silex Microframework

```php
use FFMpeg\SilexServiceProvider;
use Silex\Application;

$app = new Application();
$app->register(new FFMpegServiceProvider());
```

## API Browser

Browse the [API](http://readthedocs.org/docs/ffmpeg-php/en/latest/_static/API/)

## License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).




