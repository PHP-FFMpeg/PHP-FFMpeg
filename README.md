#PHP FFmpeg

[![Build Status](https://secure.travis-ci.org/alchemy-fr/PHP-FFmpeg.png?branch=master)](http://travis-ci.org/alchemy-fr/PHP-FFmpeg)

An Object Oriented library to convert video/audio files with AVConv / FFmpeg.

Check another amazing repo : [PHP FFMpeg extras](https://github.com/alchemy-fr/PHP-FFMpeg-Extras), you will find lots of Audio/Video formats there.

##Documentation

Read The Documentation at [Read The Docs !](http://readthedocs.org/docs/ffmpeg-php/)

##API Browser

Browse the [API](http://readthedocs.org/docs/ffmpeg-php/en/latest/_static/API/)

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

##License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).




