#PHP FFmpeg

[![Build Status](https://secure.travis-ci.org/alchemy-fr/PHP-FFmpeg.png?branch=master)](http://travis-ci.org/alchemy-fr/PHP-FFmpeg)

An Object Oriented library to convert video/audio files with AVConv / FFmpeg.

##Documentation

Read The Documentation at http://readthedocs.org/docs/ffmpeg-php/ !

##Usage Example

```php
$WebMFormat = new Format\Video\WebM();
$x264Format = new Format\Video\X264();
$OggFormat  = new Format\Video\Ogg();

$x264->setDimensions(320, 240);

$ffmpeg->open('Video.mpeg')
    ->encode($WebMFormat, 'file.webm')
    ->encode($x264Format, 'file.mp4')
    ->encode($OggFormat, 'file.ogv')
    ->close();
```

##License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).




