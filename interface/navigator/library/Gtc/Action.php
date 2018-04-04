<?php
class Gtc_Action extends Mi2_Adt_AbstractModel
{
    const START_PROGRESS = 'start-progress';
    const STOP_PROGRESS = 'stop-progress';
    
    protected $_actionKey = 0;
    protected $_parentKey = 0;
    
    protected $_actionId = null;
    protected $_parentId = null;
    protected $_caseId = null;
    protected $_actionTypeId = null;
    protected $_stateId = null;
    protected $_title = null;
    protected $_dueDate = null;
    protected $_description = null;
    protected $_timestamp = null;
    
    protected $_actionMeta = null;
    protected $_state = null;
    protected $_actionType = null;
    protected $_children = array();
    
    protected $_case = null;
    protected $_stateManager = null;
    protected $_observers = array();
    
    public function __construct( array $options = null )
    {
        parent::__construct( $options );
        $this->_stateManager = new Gtc_State_StateManager();
    }
    
    public function getActionKey()
    {
        return $this->_actionKey;
    }
    
    public function setActionKey( $actionKey )
    {
        $this->_actionKey = $actionKey;
    }
  
    public function getParentKey()
    {
        return $this->_parentKey;
    }
    
    public function setParentKey( $parentKey )
    {
        $this->_parentKey = $parentKey;
    }
    
    public function addObserver( Gtc_Action_ActionObserverIF $observer )
    {
        if ( isset( $this->_observers[$observer->getObserverId()] ) ) {
            throw new Exception( "Action Observer IDs must me unique" );
        } else {
            $this->_observers[$observer->getObserverId()]= $observer;
        }
        return $this;
    }
    
    public function inProgress()
    {
        if ( $this->getMeta( 'action_status' ) == 'start-progress' ) {
            return true;
        }
        
        return false;
    }
    
    public function startProgress()
    {
        $this->setMeta( 'action_status', self::START_PROGRESS );
        foreach ( $this->_observers as $observer ) {
            $observer->onStartProgress();
        }
    }
    
    public function stopProgress()
    {
        $this->setMeta( 'action_status', self::STOP_PROGRESS );
        foreach ( $this->_observers as $observer ) {
            $observer->onStopProgress();
        }
    }
    
    public function getLegalStates()
    {
        return $this->_stateManager->fetchAll();
    }
    
    public function getState()
    {
        return $this->_state;
    }
    
    public function setState( Gtc_State $state )
    {
        $this->_stateId = $state->getStateId();
        $this->_state = $state;
    }
    
    public function getActionType()
    {
        return $this->_actionType;
    }
    
    public function setActionType( Gtc_Action_ActionType $actionType )
    {
        $this->_actionType = $actionType;
    }
    
    public function addChild( Gtc_Action $action )
    {
        $this->_children[]= $action;
    }
    
    public function getChildren()
    {
        return $this->_children;
    }
    
    public function getStateMachine()
    {
        return $this->_stateMachine;
    }
    
    public function setStateMachine( Gtc_State_StateMachine $stateMachine )
    {
        $this->_stateMachine = $stateMachine;
    }
    
    public function getMeta( $key )
    {
        if ( $this->_actionMeta === null ) {
            $this->_actionMeta = new Gtc_Action_ActionMeta( $this->_actionId );
        }
        return $this->_actionMeta->{$key};
    }
    
    public function setMeta( $key, $value = null )
    {
        if ( $this->_actionMeta === null ) {
            $this->_actionMeta = new Gtc_Action_ActionMeta( $this->_actionId );
        }
        $this->_actionMeta->{$key} = $value;
    }
    
    public function getActionId()
    {
        return $this->_actionId;
    }
    
    public function getParentId()
    {
        return $this->_parentId;
    }
    
    public function getCaseId()
    {
        return $this->_caseId;
    }
    
    public function getActionTypeId()
    {
        return $this->_actionTypeId;
    }
    
    public function getStateId()
    {
        return $this->_stateId;
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function getDueDate()
    {
        if ( $this->_dueDate && strpos( $this->_dueDate, '0000-00-00' ) === false ) {
            $parts = explode( " ", $this->_dueDate );
            return oeFormatShortDate( $parts[0] );
        }     
        return xl('Anytime');
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
    
    public function getCase()
    {
        return $this->_case;
    }
    
    public function setCase( Gtc_Case $case )
    {
        $this->_case = $case;
    }
}
