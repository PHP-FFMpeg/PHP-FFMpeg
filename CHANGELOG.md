# Changelog

All Notable changes to the library will be documented in this file

## 1.0.1 - 2022-02-22

- Added configuration key to customize the temporary directory used for passes.
- Fix for the path of the default `ffmpeg-passes*` temporary directory.

## 1.0.0 - 2022-02-09

Upgraded dependencies, integrated the Alchemy Binary library, and dropped support for anything below PHP 8.0

- Support for Symfony 5.4 and 6.0
- Support for `psr/log` v3
- GitHub actions against FFmpeg 4.4 and 5.0
- Integrated the Alchemy Binary library
- Replaced `neutron/temporary-filesystem` with `spatie/temporary-directory`
- PHPUnit 9.5
- Removed Silex Service Provider
- Removed the auto-generated docs
- Removed support for anything below PHP 8.0
