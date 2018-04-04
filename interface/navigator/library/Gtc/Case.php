<?php
class Gtc_Case extends Mi2_Adt_AbstractModel
{    
    protected $_caseId = null;
    protected $_clientId = null;
    protected $_creatorId = null;
    protected $_stateId = null;
    protected $_timestamp = null;
    protected $_reference = null;
    protected $_referenceCode = null;
    
    protected $_owners = array();
    protected $_actions = array();
    protected $_client = null;
    
    public function getCaseId()
    {
        return $this->_caseId;
    }
    
    public function getClientId()
    {
        return $this->_clientId;
    }
    
    public function getCreatorId()
    {
        return $this->_creatorId;
    }
    
    public function getStateId()
    {
        return $this->_stateId;
    }
    
    public function getTimestamp()
    {
        return $this->_timestamp;
    }
    
    public function getReference()
    {
        return $this->_reference;
    }
    
    public function getReferenceCode()
    {
        return $this->_referenceCode;
    }
    
    public function getClient()
    {
        return $this->_client;
    }
    
    public function setClient( Gtc_Client $client )
    {
        $this->_client = $client;
    }
    
    public function addOwner( Gtc_Owner $owner )
    {
        $this->_owners[]= $owner;
    }
    
    public function getOwners()
    {
        return $this->_owners;
    }
    
    public function setOwners( array $owners )
    {
        $this->_owners = $owners;
    }
    
    public function addAction( Gtc_Action $action )
    {
        $this->_actions[]= $action;
    }
    
    public function getActions()
    {
        return $this->_actions;
    }
    
    public function getActionCount()
    {
        $count = 0;
        foreach ( $this->getActions() as $action ) {
            $count += $this->countChildren( $action );
            $count++;
        }
        
        return $count;
    }
    
    protected function countChildren( Gtc_Action $action )
    {
        $count = 0;
        if ( count( $action->getChildren() ) ) {
            foreach ( $action->getChildren() as $child ) {
                $count += $this->countChildren( $child  );
                $count++;
            }
        }
        return $count;
    }
}
