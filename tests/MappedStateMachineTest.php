<?php

namespace Krixon\StateMachine\Test;

use Krixon\StateMachine\Exception\IllegalTransition;
use Krixon\StateMachine\Exception\UnknownState;
use Krixon\StateMachine\MappedStateMachine;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Krixon\StateMachine\MappedStateMachine
 * @covers ::<protected>
 * @covers ::<private>
 */
class MappedStateMachineTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testInitialStateIsActiveOnConstruction() : void
    {
        $state = $this->defaultMachine();

        $this->assertTrue($state->is('INITIAL'));
    }

    /**
     * @covers ::current
     */
    public function testCanGetCurrentState() : void
    {
        $state = $this->defaultMachine();

        self::assertSame('INITIAL', $state->current());
    }

    /**
     * @covers ::is
     */
    public function testCanDetermineIfSpecifiedStateIsCurrent() : void
    {
        $state = $this->defaultMachine();

        self::assertTrue($state->is('INITIAL'));

        $state->transition('FIRST');

        self::assertTrue($state->is('FIRST'));
    }

    /**
     * @covers ::is
     */
    public function testCanDetermineIfCurrentStateIsOneOfList() : void
    {
        $state = $this->defaultMachine();

        self::assertTrue($state->is('INITIAL', 'FIRST'));

        $state->transition('FIRST');

        self::assertTrue($state->is('INITIAL', 'FIRST'));

        $state->transition('SECOND');

        self::assertFalse($state->is('INITIAL', 'FIRST'));
    }

    /**
     * @covers ::transition
     */
    public function testCanTransitionSuccessfullyBetweenStates() : void
    {
        $state = $this->defaultMachine();

        $state->transition('FIRST');

        self::assertSame('FIRST', $state->current());

        $state->transition('SECOND');

        self::assertSame('SECOND', $state->current());

        $state->transition('THIRD');

        self::assertSame('THIRD', $state->current());
    }

    /**
     * @covers ::transition
     */
    public function testTransitioningToCurrentStateSilentlySucceeds() : void
    {
        $state = $this->defaultMachine();

        self::assertNull($state->transition('INITIAL')); // This will throw if the test fails.
    }

    /**
     * @covers ::transition
     */
    public function testThrowsWhenTransitioningToUnknownState() : void
    {
        $state = $this->defaultMachine();

        self::expectException(UnknownState::class);

        $state->transition('FOOBAR');
    }

    /**
     * @covers ::transition
     */
    public function testThrowsWhenTransitioningToInvalidState() : void
    {
        $state = $this->defaultMachine();

        self::expectException(IllegalTransition::class);

        $state->transition('THIRD');
    }

    /**
     * @covers ::list
     */
    public function testListAllKnownStates() : void
    {
        $state = $this->defaultMachine();

        self::assertEqualsCanonicalizing(['INITIAL', 'FIRST', 'SECOND', 'THIRD'], $state->list());
    }

    /**
     * @covers ::allowed
     */
    public function testListAllowedStatesForTransitionFromCurrent() : void
    {
        $state = $this->defaultMachine();

        self::assertEqualsCanonicalizing(['FIRST', 'SECOND'], $state->allowed());

        $state->transition('SECOND');

        self::assertEqualsCanonicalizing(['FIRST', 'THIRD'], $state->allowed());
    }

    /**
     * @covers ::allowed
     */
    public function testListAllowedStatesForTransitionFromSpecified() : void
    {
        $state = $this->defaultMachine();

        self::assertEqualsCanonicalizing(['FIRST', 'THIRD'], $state->allowed('SECOND'));
    }

    private function defaultMachine() : MappedStateMachine
    {
        return new MappedStateMachine(
            [
                'INITIAL' => ['FIRST', 'SECOND'],
                'FIRST'   => ['SECOND'],
                'SECOND'  => ['FIRST', 'THIRD'],
                'THIRD'   => [],
            ],
            'INITIAL'
        );
    }
}



