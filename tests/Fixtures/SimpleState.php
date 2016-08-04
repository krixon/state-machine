<?php

namespace Krixon\StateMachine\Test\Fixtures;

use Krixon\StateMachine\MappedState;

class SimpleState extends MappedState
{
    protected function validTransitionMap()
    {
        return [
            'INITIAL' => ['FIRST', 'SECOND'],
            'FIRST'   => ['SECOND'],
            'SECOND'  => ['FIRST', 'THIRD'],
            'THIRD'   => [],
        ];
    }
    
    protected function initialState()
    {
        return 'INITIAL';
    }
}
