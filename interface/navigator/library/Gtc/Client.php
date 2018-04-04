<?php
class Gtc_Client extends Mi2_Adt_AbstractModel
{
    protected $_pubpid = null;
    protected $_firstName = null;
    protected $_lastName = null;
    protected $_dob = null;
    protected $_pid = null;
    
    
    public function getPubpid()
    {
        return $this->_pubpid;
    }
    
    public function getFirstName()
    {
        return $this->_firstName;
    }
    
    public function getLastName()
    {
        return $this->_lastName;
    }
    
    public function getFullName()
    {
        return $this->getFirstName()." ".$this->getLastName();
    }
    
    public function getDob()
    {
        return $this->_dob;
    }
    
    public function getPid()
    {
        return $this->_pid;
    }
}
