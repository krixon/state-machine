<?php

declare(strict_types=1);

namespace Krixon\StateMachine\Exception;

use DomainException;
use Throwable;
use function implode;

class IllegalTransition extends DomainException implements StateMachineError
{
    public function __construct(string $from, string $to, array $valid, Throwable $previous = null)
    {
        $message = sprintf(
            'Transitioning from state %s to %s is illegal. Allowed states for this transition: [%s]',
            $from,
            $to,
            implode(', ', $valid)
        );

        parent::__construct($message, 0, $previous);
    }
}