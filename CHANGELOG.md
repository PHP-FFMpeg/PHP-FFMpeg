CHANGELOG
---------

* 0.3.1 (xx-xx-2013)

  * Allow use of FFProbe on remote URIs.
  * Fix #47 : MediaTypeInterface::save adds filters depending on the codec.
  * Save frame to target file without prompt.

* 0.3.0 (07-04-2013)

  * Complete rewrite of the library, lots of BC breaks, check the doc.

* 0.2.4 (05-10-2013)

  * Add Video\ResizableInterface::getModulus method for better output scaling (@retrojunk)
  * Fix timeout setting on audio/video encoding (@xammep-ua)

* 0.2.3 (04-21-2013)

  * Add timeout getter and setter on FFMpeg and FFProbe
  * Add timeout setting via second argument on FFMpeg::load and FFProbe::load

* 0.2.2 (02-11-2013)

  * Add compatibility with FFMpeg 1.1
  * Upgrade deprecated options (`-ab`, `-qscale` and `-b`)
  * Use of a custom stat file for each multi-pass encoding (fix #20)
  * Use larger version range for dependencies

* 0.2.1 (02-04-2013)

  * Parse the output of FFProbe using correct EOL sequences (@ak76)
  * Add process timeout customization (@pulse00)
  * Fix `accurate` option (`FFMpeg::extractImage`)

* 0.2.0 (12-13-2012)

  * Add HelperInterface and support for realtime progress ( @pulse00 ).
  * Add `accurate` option to `FFMpeg::extractImage` method.

* 0.1.0 (10-30-2012)

  * First stable version.
