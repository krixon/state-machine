<?php

namespace Krixon\StateMachine\Exception;

/**
 * Thrown when an invalid state is encountered.
 */
class InvalidStateException extends \OutOfBoundsException
{
    public function __construct($invalid, array $valid, \Exception $previous = null)
    {
        $message = "Invalid state: $invalid. Valid states are: [" . implode(',', $valid) . ']';
        
        parent::__construct($message, 0, $previous);
    }
}
