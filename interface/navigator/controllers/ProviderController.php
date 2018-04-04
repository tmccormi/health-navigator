<?php

class ProviderController extends Mi2_Mvc_BaseController 
{   
    public function _action_view()
    {
        $userId = $this->getRequest()->getParam( 'id' );
        $statement = "SELECT * FROM users WHERE id = ?";
        $row = sqlQuery( $statement, array( $userId) );
        $this->view->clientManager = new Gtc_Client_ClientManager();
        $this->view->userId = $userId;
        $this->view->firstName = $row['fname'];
        $this->view->lastName = $row['lname'];
        $this->view->slotsPledged = $row['provider_slots_pledged'];
        $this->view->slotsUsed = $row['provider_slots_used'];
        $statement = "SELECT * FROM form_gtc_referral R JOIN patient_data P ON R.pid = P.pid WHERE provider_id = ?";
        $result = sqlStatement( $statement, array( $userId ) );
        while ( $row = sqlFetchArray( $result ) ) {
            $client = new Gtc_Client( array( 'pubpid' => $row['pubpid'], 'firstName' => $row['fname'], 'lastName' => $row['lname'] ) );
            $clients []= $client;
        }
        $this->view->clients = $clients;
        
        
        $this->setViewScript( 'provider/view.php' );
    }
}

