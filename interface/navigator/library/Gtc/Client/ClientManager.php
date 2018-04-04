<?php
class Gtc_Client_ClientManager
{
    public function getNumReferrals( $pid, $providerId )
    {
        // TODO
        return 1;
    }
    
    public function find( $pid )
    {
        $idFilter = new Mi2_Db_SearchFilter( $pid, 'PD', 'pid', Mi2_Db_SearchFilter::TYPE_STRICT );
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
        $clients = array();
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $client = new Gtc_Client( array(
                    'pubpid' => $row['pubpid'],
                    'firstName' => $row['fname'],
                    'lastName' => $row['lname'],
                    'dob' => $row['DOB'],
                    'pid' => $row['pid']
            ) );
            $clients[]= $client;
        }
        
        return $clients;
    }
    
    public function fetchAll( array $filters = null )
    {
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'PD' => 'patient_data' ) );
        foreach ( $filters as $filter ) {
            if ( $filter instanceof Mi2_Db_SearchFilter ) {
                $sql->addSearchFilter( $filter );
            }
        }
        
        return $this->execute( $sql );
    }
}
