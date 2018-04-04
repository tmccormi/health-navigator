<?php
class Gtc_ActiveUser extends Mi2_Adt_AbstractModel
{
    protected $_username = null;
    protected $_userId = null;
    protected $_groups = array();
    
    public function getUsername()
    {
        return $this->_username;
    }
    
    public function getUserId()
    {
        return $this->_userId;
    }
    
    public function getGroups()
    {
        return $this->_groups;
    }
    
    public function addGroup( $group )
    {
        $this->_groups[]=$group;
    }
}
