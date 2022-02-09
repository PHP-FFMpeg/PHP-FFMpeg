<?php

/*
 * This file is part of Alchemy\BinaryDriver.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\BinaryDriver\Exception;

class ExecutionFailureException extends \RuntimeException implements ExceptionInterface
{
    /** @var string */
    protected $command;

    /** @var string */
    protected $errorOutput;

    public function __construct($binaryName, $command, $errorOutput = null, $code = 0, $previous = null)
    {
        $message = sprintf("%s failed to execute command %s:\n\nError Output:\n\n %s", $binaryName, $command, $errorOutput);
        parent::__construct($message, $code, $previous);
        $this->command = $command;
        $this->errorOutput = $errorOutput;
    }

    public function getCommand(){
        return $this->command;
    }

    public function getErrorOutput(){
        return $this->errorOutput;
    }
}
