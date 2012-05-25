PHP FFMpeg documentation
========================

.. toctree::
   :hidden:

   Recipes

Introduction
------------

PHP-FFmpeg is an object oriented PHP wrapper for FFmpeg (AVconv on recent Linux
distribution). This tool gives access to the tools provided by FFmpeg such as
FFprobe and FFmpeg. These are the two binary currently supported by PHP-FFmpeg.

This library depends on `Symfony Process Component <https://github.com/symfony/process>`_
and `Monolog <https://github.com/Seldaek/monolog>`_.

Design
------

This library is designed to deal with legacy binaries of FFMpeg. We do not rely
on the FFmpeg extension.

This library is object oriented, this means that nearly everything is an object,
or ought to be. So you won't find any out of-the-box function to extract
images or encode video. This provides a testable design and a fail fast behavior.
We hope you'll enjoy it !

Installation
------------

We rely on `composer <http://getcomposer.org/>`_ to use this library. If you do
no still use composer for your project, you can start with this ``composer.json``
at the root of your project:

.. code-block:: json

    {
        "require": {
            "php-ffmpeg/php-ffmpeg": "master"
        }
    }

Install composer :

.. code-block:: bash

    # Install composer
    curl -s http://getcomposer.org/installer | php
    # Upgrade your install
    php composer.phar install

You now just have to autoload the library to use it :

.. code-block:: php

    <?php
    require 'vendor/autoload.php';

This is a very short intro to composer.
If you ever experience an issue or want to know more about composer,
you will find help on their  website
`http://getcomposer.org/ <http://getcomposer.org/>`_.

Basic Usage
-----------

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

    // open a video, extract an image at second 5 and 12 then close
    $ffmpeg->open('Video.mpeg')
           ->extractImage(5, 'first-screenshot.jpg')
           ->extractImage(12, 'second-screenshot.jpg')
           ->close();

Recipes
-------

You'll find usefull recipes in our recipe guide

Handling Exceptions
-------------------

PHP-FFMpeg throws 4 different types of exception :

- ``\FFMpeg\Exception\BinaryNotFoundException`` is thrown when no acceptable
  pdf2text binary is found.
- ``\FFMpeg\Exception\InvalidArgumentException`` is thrown when an invalid
  argument (file, format, ...) is provided
- ``\FFMpeg\Exception\LogicException`` which extends SPL LogicException
- ``\FFMpeg\Exception\RuntimeException`` which extends SPL RuntimeException

All these Exception implements ``\FFMpeg\Exception\Exception`` so you can catch
any of these exceptions by catching this exception interface.

Report a bug
------------

If you experience an issue, please report it in our
`issue tracker <https://github.com/alchemy-fr/PHP-FFmpeg/issues>`_. Before
reporting an issue, please be sure that it is not already reported by browsing
open issues.

When reporting, please give us information to reproduce it by giving your
platform (Linux / MacOS / Windows) and its version, the version of PHP you use
(the output of ``php --version``), the version of ffmpeg or avconv you use
(the output of ``ffmpeg -version``) and the codec configuration (``ffmpeg -formats``)

Ask for a feature
-----------------

We would be glad you ask for a feature ! Feel free to add a feature request in
the `issues manager <https://github.com/alchemy-fr/PHP-FFmpeg/issues>`_ on GitHub !

Contribute
----------

You find a bug and resolved it ? You added a feature and want to share ? You
found a typo in this doc and fixed it ? Feel free to send a
`Pull Request <http://help.github.com/send-pull-requests/>`_ on GitHub, we will
be glad to merge your code.

Run tests
---------

PHP-FFMpeg relies on `PHPUnit <http://www.phpunit.de/manual/current/en/>`_ for
unit tests. To run tests on your system, ensure you have PHPUnit installed,
and, at the root of PHP-XPDF (vendor/php-ffmpeg/php-ffmpeg), execute it :

.. code-block:: bash

    phpunit

About
-----

PHP-FFMpeg has been written by Romain Neutron @ `Alchemy <http://alchemy.fr/>`_
for `Phraseanet <https://github.com/alchemy-fr/Phraseanet>`_, our DAM software.
Try it, it's awesome !

License
-------

PHP-FFMpeg is licensed under the `MIT License <http://opensource.org/licenses/MIT>`_
