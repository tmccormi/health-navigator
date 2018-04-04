<?php
class Gtc_Action_ActionManager
{
    public static function getDefaultStateByActionTypeId( $actionTypeId )
    {
        // TODO
        return 1;    
    }
    
    public static function setActiveActionId( $actionId )
    {
        $_SESSION['active-action-id'] = $actionId;
    }
    
    public static function getActiveActionId()
    {
        return $_SESSION['active-action-id'];
    }
    
    public static function getActionForEncounterId( $encounterId )
    {
        
    }
    
    public function createEncounterForAction( Gtc_Action $action, $clientId = null )
    {
        require_once( $GLOBALS['srcdir']."/forms.inc" );
        // Code taken from forms/newpatient/save.php
        $conn = $GLOBALS['adodb']['db'];
        $pid = $clientId;
        $creatorId = $_SESSION['authUserID'];
        $userauthorized = empty($_SESSION['userauthorized']) ? 0 : $_SESSION['userauthorized'];
        $encounter = $conn->GenID("sequences");
        $date = date( 'Y-m-d' );
        $onset_date = date( 'Y-m-d' );
        $sensitivity = 'normal';
        $pc_catid = 14; // TODO add to settings. Generic Navigation Action
        $facility_id = 3; // TODO select facility
        $billing_facility = 3; // TODO select billing facility;
        $title = $action->getTitle();
        $reason = $action->getDescription();
        $referral_source = '';
        addForm( $encounter, $title,
                sqlInsert("INSERT INTO form_encounter SET " .
                        "date = '$date', " .
                        "onset_date = '$onset_date', " .
                        "reason = '$reason', " .
                        "facility = '" . add_escape_custom($facility) . "', " .
                        "pc_catid = '$pc_catid', " .
                        "facility_id = '$facility_id', " .
                        "billing_facility = '$billing_facility', " .
                        "sensitivity = '$sensitivity', " .
                        "referral_source = '$referral_source', " .
                        "pid = '$pid', " .
                        "encounter = '$encounter', " .
                        "provider_id = '$provider_id'"),
                "newpatient", $pid, $userauthorized, $date);
        
        return $encounter;
    }
    
    public function fetchInProgress( $username )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'A' => 'gtc_action', 'AM' => 'gtc_action_meta' ) );
        $sql->where( 'A.action_id = AM.action_id AND AM.username = ? AND AM.meta_key = ?', array( $username, 'action_status' ) );
        $sql->addSortOrder( new Mi2_Db_SortOrder( 'AM.timestamp', Mi2_Db_SortOrder::SORT_DESC ) );
        $sql->groupBy( 'A.action_id' );
        $all = $this->execute( $sql );
        $actions = array();
        foreach ( $all as $a ) {
            if ( $a->inProgress() ) {
                $actions[]= $a;
            }
        }
        return $actions; 
    }
    
    public function find( $actionId )
    {
        $idFilter = new Mi2_Db_SearchFilter( $actionId, 'A', 'action_id', Mi2_Db_SearchFilter::TYPE_STRICT );
        $actions = $this->fetchAll( array( $idFilter ) );
        $search = null;
        foreach ( $actions as $action ) {
            $search = $action;
            break;
        }
        return $search;
    }
    
    protected function execute( Mi2_Db_Sql $sql )
    {
        $actions = array();
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $action = new Gtc_Action( array(
                    'actionId' => $row['action_id'],
                    'parentId' => $row['parent_id'],
                    'caseId' => $row['case_id'],
                    'actionTypeId' => $row['action_type_id'],
                    'stateId' => $row['state_id'],
                    'title' => $row['title'],
                    'dueDate' => $row['due_date'],
                    'description' => $row['description'],
                    'timestamp' => $row['timestamp']
            ) );
            $actions[]= $action;
        }
        
        return $actions;
    }
    
    public function fetchAll( array $filters = null )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'A' => 'gtc_action' ) );
        foreach ( $filters as $filter ) {
            if ( $filter instanceof Mi2_Db_SearchFilter ) {
                $sql->addSearchFilter( $filter );
            }
        }
        
        return $this->execute( $sql );
    }

    public function fetchByCaseId( $caseId )
    {
        $caseFilter = new Mi2_Db_SearchFilter( $caseId, 'A', 'case_id', Mi2_Db_SearchFilter::TYPE_STRICT );
        return $this->fetchAll( array( $caseFilter ) );
    }
    
    public function buildStateMachineForAction( Gtc_Action $action )
    {
        $stateMachine = new Gtc_State_StateMachine();
        return $stateMachine;
    }
    
    public function assemble( Gtc_Action $action )
    {
        $stateManager = new Gtc_State_StateManager();
        $state = $stateManager->find( $action->getStateId() );
        $action->setState( $state );
        $actionTypeManager = new Gtc_Action_ActionTypeManager();
        $actionType = $actionTypeManager->find( $action->getActionTypeId() );
        $action->setActionType( $actionType );
        $stateMachine = $this->buildStateMachineForAction( $action );
        $action->setStateMachine( $stateMachine );
        return $action;
    }
    
    public function findAndAssemble( $actionId )
    {
        $action = $this->find( $actionId );
        $action = $this->assemble( $action );
        return $action;
    }

    public function assembleByCaseId( $caseId )
    {
        $baseActions = $this->fetchByCaseId( $caseId );
        $stateManager = new Gtc_State_StateManager();
        $actionTypeManager = new Gtc_Action_ActionTypeManager();
        $count = 1;
        $actions = array();
        foreach ( $baseActions as $ra ) {
            if ( $ra instanceof Gtc_Action ) {
                $state = $stateManager->find( $ra->getStateId() );
                $ra->setState( $state );
                $actionType = $actionTypeManager->find( $ra->getActionTypeId() );
                if ( $actionType ) {
                    $ra->setActionType( $actionType );
                    $stateMachine = $this->buildStateMachineForAction( $ra );
                    $ra->setStateMachine( $stateMachine );
                    $ra->setActionKey( $count );
                    $count++;
                    $actions[$ra->getActionId()]= $ra;
                } else {
                    error_log( "No action type found for ID = ".$ra->getActionTypeId() );
                }
            }
        }
        
        // Copy the array
        $actionTree = $actions;
        foreach ( $actions as $action ) {
            if ( $action->getParentId() > 0 ) {
                $parent = $this->findParent( $action->getParentId(), $actionTree );
                $parent->addChild( $action );
                $action->setParentKey( $parent->getActionKey() );
                unset( $actionTree[$action->getActionId()] );
            } 
        }
        
        return $actionTree;
    }
    
    protected function findParent( $id, array $array )
    {
        foreach ( $array as $elem ) {
            if ( $elem instanceof Gtc_Action ) {
                if ( $elem->getActionId() == $id ) {
                    return $elem;
                } else if ( count( $elem->getChildren() ) ) {
                    $parent = $this->findParent( $id, $elem->getChildren() );
                    if ( $parent ) {
                        return $parent;
                    }
                }
            }
        }
        
        return null;
    }
    
    public function save( Gtc_Action $action )
    {
        $id = 0;
        $sql = new Mi2_Db_Sql();
        if ( $action->getActionId() !== null ) {
            // we have an id, so perform an update
            $update = new Mi2_Db_Update( 'gtc_action', array(
                    'action_id' => $action->getActionId(),
                    'parent_id' => $action->getParentId(),
                    'case_id' => $action->getCaseId(),
                    'action_type_id' => $action->getActionTypeId(),
                    'state_id' => $action->getStateId(),
                    'title' => $action->getTitle(),
                    'due_date' => $action->getDueDate(),
                    'description' => $action->getDescription() ) );
            $update->where( new Mi2_Db_SearchFilter( $action->getActionId(), 'gtc_action', 'action_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
            $sql->update( $update );
            $id = $action->getActionId();
        } else {
            // else, input
            $insert = new Mi2_Db_Insert( 'gtc_action', array(
                    'action_id' => $action->getActionId(),
                    'parent_id' => $action->getParentId(),
                    'case_id' => $action->getCaseId(),
                    'action_type_id' => $action->getActionTypeId(),
                    'state_id' => $action->getStateId(),
                    'title' => $action->getTitle(),
                    'due_date' => $action->getDueDate(),
                    'description' => $action->getDescription() ) );
            $id = $sql->insert( $insert );
        }
        
        return $id;
    }
}
