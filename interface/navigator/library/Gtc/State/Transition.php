<?php
class Gtc_State_Transition extends Mi2_Adt_AbstractModel
{
    protected $_transitionId = null;
    protected $_actionTypeId = null;
    protected $_fromStateId = null;
    protected $_toStateId = null;
    
    protected $_fromState = null;
    protected $_toState = null;
    
    public function getFromState()
    {
        return $this->_fromState;
    }
    
    public function setFromState( Gtc_State $state )
    {
        $this->_fromState = $state;
    }
    
    public function getToState()
    {
        return $this->_toState;
    }
    
    public function setToState( Gtc_State $state )
    {
        $this->_toState = $state;
    }

    public function getTransitionId()
    {
        return $this->_transitionId;
    }
    
    public function getActionTypeId()
    {
        return $this->_actionTypeId;
    }
    
    public function getFromStateId()
    {
        return $this->_fromStateId;
    }
    
    public function getToStateId()
    {
        return $this->_toStateId;
    }
}
