<?php
class Gtc_State_StateMachine
{
    protected $_stateMap = array();
    protected $_transitionMap = array();
    protected $_transitionStateMap = array();
    
    public function getLegalStatesFrom( Gtc_State $state )
    {
        $transitions = $this->_stateMap[$state->getStateId()];
        $legalStates = array();
        if ( is_array( $transitions ) ) {
            foreach ( $transitions as $transition ) {
                $legalStates[]= $transition->getToState();
            }
        }
        return $legalStates;
    }
    
    /**
     * 
     * @param Gtc_State_Transition $transition
     * 
     * Add a transition to the state map based on the fromState ID
     */
    public function addStateTransition( Gtc_State_Transition $transition ) 
    {
        $this->_transitionMap[$transition->getTransitionId()] = $transition;
        $transitions = array();
        if ( array_key_exists( $transition->getFromStateId(), $this->_stateMap ) ) {
            $transitions = $this->_stateMap[$transition->getFromStateId()];
        }
        $transitions[$transition->getFromStateId()] = $transition;
        $this->_stateMap[$transition->getFromStateId()]= $transitions;
    }
}
