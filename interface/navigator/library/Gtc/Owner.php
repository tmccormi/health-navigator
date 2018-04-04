<?php
class Gtc_Owner extends Mi2_Adt_AbstractModel
{
    protected $_ownerId = null;
    protected $_caseId = null;
    protected $_userId = null;
    protected $_group = null;
    
    protected $_username = null;
    
    public function getUsername()
    {
        return $this->_username;
    }
    
    public function getOwnerId()
    {
        return $this->_ownerId;
    }
    
    public function getCaseId()
    {
        return $this->_caseId;
    }
    
    public function getUserId()
    {
        return $this->_userId;
    }
    
    public function getGroup()
    {
        return $this->_group;
    }
}
