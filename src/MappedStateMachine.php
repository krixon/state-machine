<?php

declare(strict_types=1);

namespace Krixon\StateMachine;

use Krixon\StateMachine\Exception\IllegalTransition;
use Krixon\StateMachine\Exception\UnknownState;
use function array_key_exists;
use function array_keys;
use function in_array;

class MappedStateMachine implements StateMachine
{
    private $map;
    private $current;

    /**
     * The provided map should be an array where each element's key is a state, and each value is an array of states
     * to which it is valid to transition. Every state must exist in the array. If there are no valid transitions
     * from a state, its value should be an empty array.
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
     * @param string[] $map
     * @throws UnknownState if the initial state is not known.
     */
    public function __construct(array $map, string $initial)
    {
        $this->map     = $map;
        $this->current = $initial;

        $this->assertKnownState($this->current);
    }

    /**
     * @inheritDoc
     */
    public function transition(string $to) : void
    {
        if ($this->is($to)) {
            return;
        }

        $this->assertCanTransitionTo($to);

        $this->current = $to;
    }

    /**
     * @inheritDoc
     */
    public function current() : string
    {
        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function is(string ...$states) : bool
    {
        $this->assertKnownState(...$states);

        foreach ($states as $state) {
            if ($this->current === $state) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * @inheritDoc
     */
    public function list() : array
    {
        return array_keys($this->map);
    }

    /**
     * @inheritDoc
     */
    public function allowed(string $from = null) : array
    {
        if ($from) {
            $this->assertKnownState($from);
        } else {
            $from = $this->current;
        }

        return $this->map[$from];
    }

    private function assertKnownState(string ...$states) : void
    {
        foreach ($states as $state) {
            if (!array_key_exists($state, $this->map)) {
                throw new UnknownState($state, $this->list());
            }
        }
    }

    /**
     * @throws IllegalTransition If the transition is not allowed according to the map.
     */
    private function assertCanTransitionTo(string $state) : void
    {
        $allowed = $this->allowed($this->current);

        if (!in_array($state, $allowed, true)) {
            throw new IllegalTransition($this->current, $state, $allowed);
        }
    }
}