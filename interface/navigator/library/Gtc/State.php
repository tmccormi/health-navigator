<?php
class Gtc_State extends Mi2_Adt_AbstractModel
{
    protected $_stateId = null;
    protected $_name = null;
    protected $_description = null;
    
    public function getStateId()
    {
        return $this->_stateId;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
}
