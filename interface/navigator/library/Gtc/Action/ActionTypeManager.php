<?php
class Gtc_Action_ActionTypeManager
{
    public function find( $actionTypeId )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'L' => 'list_options' ) );
        $sql->where( 'L.list_id = ? AND L.option_id = ?', array( 'gtc_action_type', $actionTypeId ) );
        $sql->execute();
        $actionType = null;
        while ( $row = $sql->fetchNext() ) {
            $actionType = new Gtc_Action_ActionType( array(
                'actionTypeId' => $row['option_id'],
                'name' => $row['title'],
                'description' => $row['notes'],
                'defaultStateId' => $row['codes'] ) );
            break;
        }
    
        return $actionType;
    }
    
    public function fetchActionTypeOptions()
    {
        $actionTypes = $this->fetchAll();
        $options = array();
        foreach ( $actionTypes as $at ) {
            $options[$at->getName()] = $at->getActionTypeId();
        }
        return $options;
    }
    
    public function fetchAll()
    {
        $actionTypes = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'L' => 'list_options' ) );
        $sql->addSearchFilter( new Mi2_Db_SearchFilter( 'gtc_action_type', 'L', 'list_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $at = new Gtc_Action_ActionType( array(
                'actionTypeId' => $row['option_id'],
                'name' => $row['title'],
                'description' => $row['notes'],
                'defaultStateId' => $row['codes'] ) );
            $actionTypes[]= $at;
        }
    
        return $actionTypes;
    }
}
