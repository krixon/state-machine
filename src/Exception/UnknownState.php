<?php

declare(strict_types=1);

namespace Krixon\StateMachine\Exception;

use OutOfBoundsException;
use Throwable;
use function implode;
use function sprintf;

class UnknownState extends OutOfBoundsException implements StateMachineError
{
    public function __construct(string $unknown, array $known, Throwable $previous = null)
    {
        $message = sprintf(
            'State %s is unknown. Known states: [%s]',
            $unknown,
            implode(', ', $known)
        );

        parent::__construct($message, 0, $previous);
    }
}