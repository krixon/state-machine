<?php

namespace Krixon\StateMachine;

use Krixon\StateMachine\Exception\IllegalTransition;
use Krixon\StateMachine\Exception\UnknownState;

interface StateMachine
{
    /**
     * Transitions to a new state.
     *
     * @throws UnknownState If the new state is not known.
     * @throws IllegalTransition If it is not allowed to transition from the current state to the new state.
     */
    public function transition(string $to) : void;

    /**
     * Returns the current state.
     */
    public function current() : string;

    /**
     * Determines if the current state is one of the specified states.
     *
     * @throws UnknownState If a specified state is not known.
     */
    public function is(string ...$states) : bool;

    /**
     * Returns a list of all known states.
     *
     * @return string[]
     */
    public function list() : array;

    /**
     * Determines the allowed states when transitioning from the specified state.
     *
     * If no state is specified, the current state is assumed.
     *
     * @return string[]
     * @throws UnknownState If a state is specified but is not known.
     */
    public function allowed(string $from = null) : array;

    /**
     * Determines if this machine is in the same state as another.
     *
     * Note that this does not imply that the machines themselves are equal. For example, the set of known states
     * and allowed transitions might be different between instances, but as long as they are in the same current
     * state, they are considered to be equal ($a->current() === $b->current()).
     */
    public function equals(StateMachine $other) : bool;
}
