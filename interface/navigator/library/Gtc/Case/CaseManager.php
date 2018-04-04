<?php
class Gtc_Case_CaseManager
{    
    public static function getDefaultStateId()
    {
        return 1;
    }
    
    public function createAndSaveCaseFromRequest( Mi2_Mvc_Request $request )
    {
        $params = $request->getParams();
        $caseId = null;
        if ( $params['caseId'] ) {
            $caseId = $params['caseId'];
        }
        $stateId = self::getDefaultStateId();
        if ( $params['stateId'] ) {
            $stateId = $params['stateId'];
        }
        $case = new Gtc_Case( array( 
                'caseId' => $caseId,
                'clientId' => $params['clientId'],
                'creatorId' => $params['creatorId'],
                'stateId' => $stateId ) );
        $caseId = $this->save( $case );
        
        $ownerManager = new Gtc_Case_OwnerManager();
        $ownerId = null;
        if ( $params['ownerId'] ) {
            $ownerId = $params['ownerId'];
        }
        $owner = new Gtc_Owner( array(
                'ownerId' => $ownerId,
                'caseId' => $caseId,
                'group' => $params['group'],
                'userId' => !empty($params['userId']) ? $params['userId'] : 0
                 ) );
        $ownerManager->save( $owner );
        
        $actionManager = new Gtc_Action_ActionManager();
        $id_map = array();
        foreach ( $params['actions'] as $a ) {
            $parentKey = $a['parentKey'];
            $parentId = 0;
            if ( $parentKey ) {
                $parentId = $id_map[$parentKey];
            }
            
            $actionId = $a['actionId'];
            if ( !$actionId ) {
                $action = new Gtc_Action( array(
                        'actionId' => null,
                        'parentId' => $parentId,
                        'caseId' => $caseId,
                        'actionTypeId' => $a['actionTypeId'],
                        'stateId' => Gtc_Action_ActionManager::getDefaultStateByActionTypeId( $a['actionTypeId'] ),
                        'title' => $a['title'],
                        'dueDate' => $a['dueDate'],
                        'description' => $a['description'] ) );
                $actionId = $actionManager->save( $action );
            }
            $id_map[$a['actionKey']] = $actionId;
        }
        
        return $case;
    }
    
    public function save( Gtc_Case $case )
    {
        $id = 0;
        $sql = new Mi2_Db_Sql();
        if ( $case->getCaseId() !== null ) {
            // we have an id, so perform an update
            $update = new Mi2_Db_Update( 'gtc_case', array(
                    'case_id' => $case->getCaseId(),
                    'client_id' => $case->getClientId(),
                    'creator_id' => $case->getCreatorId(),
                    'state_id' => $case->getStateId() ) );
            $update->where( new Mi2_Db_SearchFilter( $case->getCaseId(), 'gtc_case', 'case_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
            $sql->update( $update );
            $id = $case->getCaseId();
        } else {
            // else, input
            $insert = new Mi2_Db_Insert( 'gtc_case', array(
                    'case_id' => $case->getCaseId(),
                    'client_id' => $case->getClientId(),
                    'creator_id' => $case->getCreatorId(),
                    'state_id' => $case->getStateId() ) );
            $id = $sql->insert( $insert );
        }
        
        return $id;
    }
    
    public function findAndAssemble( $caseId )
    {
        $case = $this->find( $caseId );
        $actionManager = new Gtc_Action_ActionManager();
        $actions = $actionManager->assembleByCaseId( $caseId );
        foreach ( $actions as $action ) {
            $case->addAction( $action );
        }
        
        $ownerManager = new Gtc_Case_OwnerManager();
        $owners = $ownerManager->fetchByCaseId( $caseId );
        $case->setOwners( $owners );
        
        $clientManager = new Gtc_Client_ClientManager();
        $client = $clientManager->find( $case->getClientId() );
        $case->setClient( $client );
        
        return $case;
    }
    
    public function find( $caseId )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'C' => 'gtc_case' ) );
        $sql->addSearchFilter( new Mi2_Db_SearchFilter( $caseId, 'C', 'case_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        $case = null;
        while ( $row = $sql->fetchNext() ) {
            $case = new Gtc_Case( array( 
                    'caseId' => $row['case_id'], 
                    'clientId' => $row['client_id'], 
                    'creatorId' => $row['creator_id'], 
                    'stateId' => $row['state_id'],
                    'timestamp' => $row['timestamp'] ) );
            break;
        }
        
        return $case;
    }
    
    public function fetchAll( array $filters = null )
    {
        $cases = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'C' => 'gtc_case', 'CO' => 'gtc_case_owner' ) );
        foreach ( $filters as $filter ) {
            if ( $filter instanceof Mi2_Db_SearchFilter ) {
                $sql->addSearchFilter( $filter );
            }
        }
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $case = new Gtc_Case( array( 
                    'caseId' => $row['case_id'], 
                    'clientId' => $row['client_id'], 
                    'creatorId' => $row['creator_id'], 
                    'stateId' => $row['state_id'],
                    'timestamp' => $row['timestamp'] ) );
            $cases[]= $case;
        }
        
        return $cases;
    }  
    
    public function fetchCases( array $filters = null ) 
    {
        $cases = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'C' => 'gtc_case' ) );
        foreach ( $filters as $filter ) {
            if ( $filter instanceof Mi2_Db_SearchFilter ) {
                $sql->addSearchFilter( $filter );
            }
        }
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $case = new Gtc_Case( array(
                    'caseId' => $row['case_id'],
                    'clientId' => $row['client_id'],
                    'creatorId' => $row['creator_id'],
                    'stateId' => $row['state_id'],
                    'timestamp' => $row['timestamp'] ) );
            $cases[]= $case;
        }
        
        return $cases;
    }
    
    public function fetchByPatientId( $pid )
    {
        $pidFilter = new Mi2_Db_SearchFilter( $pid, 'C', 'client_id', Mi2_Db_SearchFilter::TYPE_STRICT );
        return $this->fetchCases( array( $pidFilter ) );
    }
    
    public function fetchByOwner( Gtc_Owner $owner )
    {
        $ownerFilter = new Mi2_Db_SearchFilter( $owner->getOwnerId(), 'CO', 'owner_id', Mi2_Db_SearchFilter::TYPE_STRICT );
        $groupFilter = new Mi2_Db_SearchFilter( $owner->getGroup(), 'CO', 'group', Mi2_Db_SearchFilter::TYPE_STRICT );
        return $this->fetchAll( array( $ownerFilter, $groupFilter ) );
    }

    public function getMyCasesSql( Gtc_ActiveUser $user )
    {
        $sql = new Mi2_Db_Sql(); 
        $statement = "SELECT C.case_id, C.client_id, CO.user_id, CO.group, CO.case_id, P.pid, P.pubpid, P.fname, P.lname, (SELECT COUNT(*) FROM gtc_action ACT WHERE C.case_id = ACT.case_id ) AS action_count " .
                "FROM gtc_case C " .
                "JOIN gtc_case_owner CO ON CO.case_id = C.case_id " .
                "JOIN patient_data P ON P.pid = C.client_id " .
                "WHERE ( CO.user_id = ? OR CO.group IN ( ";
        $count = 0;
        foreach ( $user->getGroups() as $g ) {
            $statement .= "?";
            if ( $count < count($user->getGroups()) - 1 ) {
                $statement .= ",";
            }
            $count++;
        }
        $statement .= " ) )";
        $binds = array();
        $binds[]= $user->getUserId();
        $binds = array_merge( $binds, $user->getGroups() );
        $sql->query( $statement, $binds );
        return $sql;    
    }
    
    public function fetchReferenceTypeOptions()
    {
        $referenceTypes = $this->fetchAllReferenceTypes();
        $options = array();
        foreach ( $referenceTypes as $at ) {
            $options[$at->getName()] = $at->getReferenceTypeId();
        }
        return $options;
    }
    
    public function fetchAllReferenceTypes()
    {
        $referenceTypes = array();
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'L' => 'list_options' ) );
        $sql->addSearchFilter( new Mi2_Db_SearchFilter( 'GTC_Reference_Type', 'L', 'list_id', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $rt = new Gtc_Case_ReferenceType( array(
                    'referenceTypeId' => $row['option_id'],
                    'name' => $row['title'],
                    'description' => $row['notes'] ) );
            $referenceTypes[]= $rt;
        }
    
        return $referenceTypes;
    }
    

}
