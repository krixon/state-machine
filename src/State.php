<?php

namespace Krixon\StateMachine;

use Krixon\StateMachine\Exception\InvalidStateException;
use Krixon\StateMachine\Exception\InvalidTransitionException;

interface State
{
    /**
     * Returns the current state when invoked directly.
     *
     * @return mixed
     */
    public function __invoke();
    
    
    /**
     * Determines if the current state is the specified state.
     *
     * @param $state
     *
     * @return bool
     * @throws InvalidStateException If the specified state is unknown.
     */
    public function is($state) : bool;
    
    
    /**
     * Determines if the current state is one of the specified states.
     *
     * @param array $states
     *
     * @return bool
     * @throws InvalidStateException If a specified state is unknown.
     */
    public function isOneOf(array $states) : bool;
    
    
    /**
     * Returns the current state.
     *
     * @return mixed
     */
    public function get();
    
    
    /**
     * Transitions to the specified state.
     *
     * @param $newState
     *
     * @return void
     * @throws InvalidStateException      If the new state is unknown.
     * @throws InvalidTransitionException If it is not possible to transition to the state.
     */
    public function transition($newState);
}
