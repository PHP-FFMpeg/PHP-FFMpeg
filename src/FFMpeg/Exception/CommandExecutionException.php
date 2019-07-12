<?php


namespace FFMpeg\Exception;


use Alchemy\BinaryDriver\Exception\ExecutionFailureException;

class CommandExecutionException extends RuntimeException
{
    /**
     * CommandExecutionException constructor.
     * @param ExecutionFailureException $executionFailureException
     */
    public function __construct($executionFailureException, $code)
    {
        // For BinaryDriver > 5.1
        if(method_exists($executionFailureException, 'getErrorOutput')){
            $err_output = $executionFailureException->getErrorOutput();
            $message = sprintf("Encoding failed due to the following error:\n\nError Output:\n\n %s ", $err_output);
        }
        // Generic message for older versions of BinaryDriver
        else{
            $message = "Encoding failed";
        }

        parent::__construct($message, $code, $executionFailureException);

    }

}
