<?php
class Gtc_State_StateManager
{
    public function find( $stateId )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'L' => 'list_options' ) );
        $sql->where( 'L.list_id = ? AND L.option_id = ?', array( 'gtc_state', $stateId ) );
        $sql->execute();
        $state = null;
        while ( $row = $sql->fetchNext() ) {
            $state = new Gtc_State( array(
                    'stateId' => $row['option_id'],
                    'name' => $row['title'],
                    'description' => $row['notes'] ) );
            break;
        }
        
        return $state;
    }
    
    public function fetchAll()
    {
        $states = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'L' => 'list_options' ) );
        $sql->addSearchFilter( new Mi2_Db_SearchFilter( 'gtc_state', 'L', 'list_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $state = new Gtc_State( array(
                    'stateId' => $row['option_id'],
                    'name' => $row['title'],
                    'description' => $row['notes'] ) );
            $states[]= $state;
        }
        
        return $states;
    }
    
}
