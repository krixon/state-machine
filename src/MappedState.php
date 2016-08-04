<?php

namespace Krixon\StateMachine;

/**
 * A simple implementation of a StateMachine which uses a map of valid transitions provided by the concrete subclass.
 */
abstract class MappedState implements State, \Serializable
{
    
    protected $currentState;
    private $map;
    
    
    public function __construct()
    {
        $this->map          = $this->validTransitionMap();
        $this->currentState = $this->initialState();
    }
    
    
    /**
     * @inheritdoc
     */
    public function __invoke()
    {
        return $this->get();
    }
    
    
    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize($this->currentState);
    }
    
    
    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        $this->__construct();
        
        $this->currentState = unserialize($serialized);
    }
    
    
    /**
     * @inheritdoc
     */
    public function is($state) : bool
    {
        $this->assertStateExists($state);
        
        return $this->currentState === $state;
    }
    
    
    /**
     * @inheritdoc
     */
    public function isOneOf(array $states) : bool
    {
        foreach ($states as $state) {
            if ($this->is($state)) {
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     * @inheritdoc
     */
    public function get()
    {
        return $this->currentState;
    }
    
    
    /**
     * @inheritdoc
     */
    public function transition($newState)
    {
        if ($this->is($newState)) {
            // Already in the requested state, nothing to do.
            return;
        }
        
        $this->assertStateExists($newState);
        
        if (!in_array($newState, $this->map[$this->currentState], true)) {
            throw new Exception\InvalidTransitionException(
                $this->currentState,
                $newState,
                $this->map[$this->currentState]
            );
        }
        
        $this->currentState = $newState;
    }
    
    
    /**
     * Returns the map of valid transitions.
     *
     * This should be an array where each element's key is a state, and each value is an array of states to which it
     * is valid to transition. Every state must exist in the array. If there are no valid transitions from a state,
     * its value should be an empty array.
     *
     * For example, a state machine for a lockable door might look like this:
     *
     * OPEN <-> CLOSED <-> LOCKED
     *  ^_|      ^_|       ^_|
     *
     * This would be represented with the following array:
     *
     * [
     *     OPEN   => [CLOSED],
     *     CLOSED => [LOCKED, OPEN],
     *     LOCKED => [CLOSED],
     * ]
     *
     * Note that there is no need to include directly looping transitions (a state transitioning to itself) as this
     * is handled automatically.
     *
     * @return array
     */
    abstract protected function validTransitionMap();
    
    
    /**
     * Returns the initial state.
     *
     * @return mixed
     */
    abstract protected function initialState();
    
    
    /**
     * Asserts that a state exists.
     *
     * @param $state
     *
     * @throws Exception\InvalidStateException
     */
    private function assertStateExists($state)
    {
        if (!array_key_exists($state, $this->map)) {
            throw new Exception\InvalidStateException($state, array_keys($this->map));
        }
    }
}
