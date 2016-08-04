<?php

namespace Krixon\StateMachine\Test;

use Krixon\StateMachine\Exception\InvalidStateException;
use Krixon\StateMachine\Exception\InvalidTransitionException;
use Krixon\StateMachine\Test\Fixtures\SimpleState;
use Krixon\StateMachine\MappedState;

/**
 * @coversDefaultClass Krixon\StateMachine\MappedState
 * @covers ::<protected>
 * @covers ::<private>
 */
class MappedStateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testInitialStateIsActiveOnConstruction()
    {
        $state = new SimpleState;
        
        $this->assertTrue($state->is('INITIAL'));
    }
    
    
    /**
     * @covers ::serialize
     * @covers ::unserialize
     */
    public function testStateIsRestoredOnUnserialize()
    {
        $state = new SimpleState;
        
        $state->transition('FIRST');
        
        /** @var MappedState $state */
        $state = unserialize(serialize($state));
        
        self::assertSame('FIRST', $state());
    }
    
    
    /**
     * @covers ::__invoke
     * @covers ::get
     */
    public function testCanGetCurrentState()
    {
        $state = new SimpleState;
        
        self::assertSame('INITIAL', $state->get());
        self::assertSame('INITIAL', $state());
    }
    
    
    /**
     * @covers ::is
     */
    public function testCanDetermineIfSpecifiedStateIsActive()
    {
        $state = new SimpleState;
    
        self::assertTrue($state->is('INITIAL'));
        
        $state->transition('FIRST');
    
        self::assertTrue($state->is('FIRST'));
    }
    
    
    /**
     * @covers ::isOneOf
     */
    public function testCanDetermineIfStateIsOneOfList()
    {
        $state = new SimpleState;
    
        self::assertTrue($state->isOneOf(['INITIAL', 'FIRST']));
        
        $state->transition('FIRST');
    
        self::assertTrue($state->isOneOf(['INITIAL', 'FIRST']));
        
        $state->transition('SECOND');
    
        self::assertFalse($state->isOneOf(['INITIAL', 'FIRST']));
    }
    
    
    /**
     * @covers ::transition
     */
    public function testCanTransitionSuccessfullyBetweenStates()
    {
        $state = new SimpleState;
        
        $state->transition('FIRST');
        
        self::assertSame('FIRST', $state());
        
        $state->transition('SECOND');
        
        self::assertSame('SECOND', $state());
        
        $state->transition('THIRD');
        
        self::assertSame('THIRD', $state());
    }
    
    
    /**
     * @covers ::transition
     */
    public function testTransitioningToCurrentStateSilentlySucceeds()
    {
        $state = new SimpleState;
    
        self::assertNull($state->transition('INITIAL')); // This will throw if the test fails.
    }
    
    
    /**
     * @covers ::transition
     */
    public function testThrowsWhenTransitioningToUnknownState()
    {
        $state = new SimpleState;
        
        self::expectException(InvalidStateException::class);
        
        $state->transition('FOOBAR');
    }
    
    
    /**
     * @covers ::transition
     */
    public function testThrowsWhenTransitioningToInvalidState()
    {
        $state = new SimpleState;
        
        self::expectException(InvalidTransitionException::class);
        
        $state->transition('THIRD');
    }
}



