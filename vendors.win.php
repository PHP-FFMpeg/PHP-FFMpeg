<?php

/*
 * This file is largely inspired by the one written for the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Get all dependencies needed for Phraseanet (Windows Version)
 *
 * Set the variables gitDir and phpDir with a trailing slash if it is not set in Windows' %PATH%
 * For example :
 * $phpDir="c:/php5310/"
 */
call_user_func(function()
  {
      $phpDir = "";

      chdir(__DIR__);

      set_time_limit(0);

      $composer = __DIR__ . '/composer.phar';

      if ( ! file_exists($composer))
      {
          file_put_contents($composer, file_get_contents('http://getcomposer.org/installer'), LOCK_EX);
          system($phpDir . 'php ' . $composer . ' install');
      }

      system($phpDir . 'php ' . $composer . ' self-update');
      system($phpDir . 'php ' . $composer . ' update');
  });

