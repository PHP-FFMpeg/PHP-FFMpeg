<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Exception;

/**
 * Thrown when there were an error in the program logic.
 * This kind of exception should lead directly to a fix in your code.
 */
class LogicException extends \LogicException implements ExceptionInterface
{
}
