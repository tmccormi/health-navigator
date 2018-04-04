<?php
class Gtc_State_TransitionManager
{
    public function assembleByActionTypeId( $actionTypeId )
    {
        $transitions = $this->fetchByActionTypeId( $actionTypeId );
        $stateManager = new Gtc_State_StateManager();
        foreach( $transitions as $transition ) {
            if ( $transition instanceof Gtc_State_Transition ) {
                $fromState = $stateManager->find( $transition->getFromStateId() );
                $transition->setFromState( $fromState );
                $toState = $stateManager->find( $transition->getToStateId() );
                $transition->setToState( $toState );
            }
        }

        return $transitions;
    }
    
    public function fetchByActionTypeId( $actionTypeId )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'T' => 'gtc_transition' ) );
        $sql->addSearchFilter( new Mi2_Db_SearchFilter( $actionTypeId, 'T', 'action_type_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        $transitions = array();
        while ( $row = $sql->fetchNext() ) {
            $transition = new Gtc_State_Transition( array(
                    'transitionId' => $row['transition_id'],
                    'actionTypeId' => $row['action_type_id'],
                    'fromStateId' => $row['from_state_id'],
                    'toStateId' => $row['to_state_id'] ) );
            $transitions[]= $transition;
        }
        
        return $transitions;
    }
    
}
