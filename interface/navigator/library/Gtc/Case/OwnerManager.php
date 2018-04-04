<?php
class Gtc_Case_OwnerManager
{
    public function save( Gtc_Owner $owner )
    {
        $id = 0;
        $sql = new Mi2_Db_Sql();
        if ( $owner->getOwnerId() !== null ) {
            // we have an id, so perform an update
            $update = new Mi2_Db_Update( 'gtc_case_owner', array(
                    'owner_id' => $owner->getOwnerId(),
                    'case_id' => $owner->getCaseId(),
                    'group' => $owner->getGroup(),
                    'user_id' => $owner->getUserId() ) );
            $update->where( new Mi2_Db_SearchFilter( $owner->getOwnerId(), 'gtc_case_owner', 'owner_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
            $sql->update( $update );
            $id = $owner->getOwnerId();
        } else {
            // else, input
            $insert = new Mi2_Db_Insert( 'gtc_case_owner', array(
                    'owner_id' => $owner->getOwnerId(),
                    'case_id' => $owner->getCaseId(),
                    'group' => $owner->getGroup(),
                    'user_id' => $owner->getUserId() ) );
            $id = $sql->insert( $insert );
        }
        
        return $id;
    }
    
    /**
     * 
     * @param string $group value of group from gacl_aro_groups table
     * @return array of options
     */
    public function fetchUserOptions( $group = null )
    {
        $sql = new Mi2_Db_Sql();
        $statement = "SELECT U.username, U.id, ARO.id AS aro_id, AROG.value AS aro_group_value ";
        $statement .= "FROM users U ";
        $statement .= "JOIN gacl_aro ARO ON ARO.value = U.username ";
        $statement .= "JOIN gacl_groups_aro_map AROGM ON AROGM.aro_id = ARO.id ";
        $statement .= "JOIN gacl_aro_groups AROG ON AROG.id = AROGM.group_id ";
        $statement .= "WHERE AROG.value = ?";
        $sql->query( $statement, array( $group ) );
        $sql->execute();
        $options = array();
        while ( $row = $sql->fetchNext() ) {
            $options[$row['username']] = $row['id'];
        }
        return $options;
    }
    
    public function fetchGroupOptions()
    {
        $sql = new Mi2_Db_Sql();
        $statement = "SELECT AROG.name, AROG.value FROM gacl_aro_groups AROG WHERE AROG.parent_id != 0";
        $sql->query( $statement );
        $sql->execute();
        $options = array();
        while ( $row = $sql->fetchNext() ) {
            $options[$row['name']] = $row['value'];
        }
        return $options;
    }
    
    public function fetchByCaseId( $caseId )
    {
        $owners = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'O' => 'gtc_case_owner' ) );
        $sql->addFields( array( 'O.owner_id', 'O.case_id', 'O.user_id', 'O.group' ) );
        $sql->addSearchFilter( new Mi2_Db_SearchFilter( $caseId, 'O', 'case_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $username = '';
            if ( $row['user_id'] ) {
                $sql2 = new Mi2_Db_Sql();
                $sql2->create( array( 'U' => 'users' ) );
                $sql2->addFields( array( 'U.username', 'U.id' ) );
                $sql2->addSearchFilter( new Mi2_Db_SearchFilter( $row['user_id'], 'U', 'id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
                $sql2->execute();
                while ( $row2 = $sql2->fetchNext() ) {
                    $username = $row2['username'];
                    break;
                }
            }
            
            $owner = new Gtc_Owner( array(
                    'ownerId' => $row['owner_id'],
                    'caseId' => $row['case_id'],
                    'userId' => $row['user_id'],
                    'group' => $row['group'],
                    'username' => $username ) );
            
            
            $owners[]= $owner;
        }
        
        return $owners;
    }
    
    public function fetchAll( array $filters = null )
    {
        $cases = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'C' => 'gtc_case', 'O' => 'gtc_case_owner' ) );
    
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $case = new Gtc_Case( array( 
                    'caseId' => $row['case_id'], 
                    'clientId' => $row['client_id'], 
                    'creatorId' => $row['creator_id'], 
                    'stateId' => $row['state_id'] ) );
            $cases[]= $case;
        }
    
        return $cases;
    }
    
    public function findByUsername( $username )
    {
        $owner = null;
        $sql = new Mi2_Db_Sql();
        $statement = "SELECT * FROM users U WHERE U.username = ?";
        $sql->query( $statement, array( $username ) );
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $owner = new Gtc_ActiveUser( array(
                    'username' => $row['username'],
                    'userId' => $row['id'] ) );
            break;
        }
        $sql2 = new Mi2_Db_Sql();
        $statement = "SELECT * FROM gacl_aro_groups G JOIN gacl_aro ARO ON ARO.id = G.id WHERE ARO.value = ?";
        $sql2->query( $statement, array( $owner->getUsername() ) );
        $sql2->execute();
        while ( $row = $sql2->fetchNext() ) {
            $owner->addGroup( $row['value'] );
        }
        
        return $owner;
    }
}
