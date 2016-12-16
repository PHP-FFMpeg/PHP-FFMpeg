CHANGELOG
=========

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

[Unreleased]
------------

### Added

- Add pull request and issue templates.
- Usage of new codec "aac" of ffmpeg 3

### Changed

- Updated changelog to follow [keepachangelog.com](http://keepachangelog.com/)
  style you see now here.

[0.7.0] - 2016-12-15
--------------------

- Add support for FFMpeg 3 aac codec (@Nek-)
- Add a waveform filter to extract audio waveform images (@Romain)

[0.6.1] - 2016-03-08
--------------------

- Support PHP 7 and test against
- Unused code cleanup (@haphan)
- Composer and tests cleanup (PSR-4 autoloading)
- Allow usage of evenement v2.0

[0.6.0] - 2016-01-30
--------------------

- AbstractData::get no longer throws exceptions (@sujayjaju).
- Add crop filter (@cangelis).
- Fix watermark (@sujayjaju).

[0.5.1] - 2016-08-26
--------------------

- Fix video aspect ratio calculation (@nlegoff).

[0.5.0] - 2014-08-12
--------------------

- Add support for Wav and AAC audio formats (@MrHash).
- Add watermark filter (@sylvainv).
- Add configuration for audio channels (@SimonSimCity).

[0.4.4] - 2016-12-17
--------------------

- Fix width / height dimensions extraction.

[0.4.3] - 2013-02-12
--------------------

- Fix using rotate and resize filters at the same time (#78)

[0.4.2] - 2013-11-29
--------------------

- Add Rotate filter.
- Remove time_start metadata when using synchronize filter
- Remove restriction on filesystem resources.

[0.4.1] - 2013-11-26
--------------------

- Add Clip filter (@guimeira)

[0.4.0] - 2013-10-21
--------------------

- Add support for video to audio transcoding
- BC Break : Add FormatInterface::getPasses and FormatInterface::getExtraParams

[0.3.5] - 2013-10-21
--------------------

- Add vorbis audio format (@jacobbudin).
- Fix #66 : Allow single pass encodings.

[0.3.4] - 2013-09-05
--------------------

- Fix Invalid ratio computing.

[0.3.3] - 2013-09-05
--------------------

- Add convenient Stream::getDimensions method to extract video dimension.
- Add DisplayRatioFixer Frame filter.

[0.3.2] - 2013-08-08
--------------------

- Fix A/V synchronization over flash and HTML5 players.

[0.3.1] - 2013-08-06
--------------------

- Allow use of FFProbe on remote URIs.
- Fix #47 : MediaTypeInterface::save adds filters depending on the codec.
- Save frame to target file without prompt.

[0.3.0] - 2013-07-04
--------------------

- Complete rewrite of the library, lots of BC breaks, check the doc.

[0.2.4] - 2013-05-10
--------------------

- Add Video\ResizableInterface::getModulus method for better output scaling (@retrojunk)
- Fix timeout setting on audio/video encoding (@xammep-ua)

[0.2.3] - 2013-04-21
--------------------

- Add timeout getter and setter on FFMpeg and FFProbe
- Add timeout setting via second argument on FFMpeg::load and FFProbe::load

[0.2.2] - 2013-02-11
--------------------

- Add compatibility with FFMpeg 1.1
- Upgrade deprecated options (`-ab`, `-qscale` and `-b`)
- Use of a custom stat file for each multi-pass encoding (fix #20)
- Use larger version range for dependencies

[0.2.1] - 2013-02-04
--------------------

- Parse the output of FFProbe using correct EOL sequences (@ak76)
- Add process timeout customization (@pulse00)
- Fix `accurate` option (`FFMpeg::extractImage`)

[0.2.0] - 2012-12-13
--------------------

- Add HelperInterface and support for realtime progress ( @pulse00 ).
- Add `accurate` option to `FFMpeg::extractImage` method.

0.1.0 - 2012-10-30
--------------------

- First stable version.

[Unreleased]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.6.1...HEAD
[0.6.1]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.6.0...0.6.1
[0.6.0]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.5.1...0.6.0
[0.5.1]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.5.0...0.5.1
[0.5.0]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.4.4...0.5.0
[0.4.4]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.4.3...0.4.4
[0.4.3]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.4.2...0.4.3
[0.4.2]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.4.1...0.4.2
[0.4.1]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.4.0...0.4.1
[0.4.0]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.3.5...0.4.0
[0.3.5]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.3.4...0.3.5
[0.3.4]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.3.3...0.3.4
[0.3.3]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.3.2...0.3.3
[0.3.2]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.3.1...0.3.2
[0.3.1]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.2.4...0.3.0
[0.2.4]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.2.3...0.2.4
[0.2.3]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.2.2...0.2.3
[0.2.2]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/PHP-FFMpeg/PHP-FFMpeg/compare/0.1.0...0.2.0
