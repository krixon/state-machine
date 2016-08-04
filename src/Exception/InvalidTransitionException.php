<?php

namespace Krixon\StateMachine\Exception;

/**
 * Thrown when an attempt is made to transition to an invalid state.
 */
class InvalidTransitionException extends \RuntimeException
{
    public function __construct($from, $to, array $valid = [], \Exception $previous = null)
    {
        $message = "Cannot transition from state $from to $to. ";
        
        if (empty($valid)) {
            $message .= "There are no states which can be reached from $from.";
        } else {
            $message .= "Valid states from $from are [" . implode(',', $valid) . ']';
        }
        
        parent::__construct($message, 0, $previous);
    }
}
