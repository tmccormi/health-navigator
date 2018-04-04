<?php
class Gtc_Action_ActionType extends Mi2_Adt_AbstractModel
{
    protected $_actionTypeId = null;
    protected $_name = null;
    protected $_description = null;
    protected $_defaultStateId = null;
    
    public function getActionTypeId()
    {
        return $this->_actionTypeId;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
    
    public function getDefaultStateId()
    {
        return $this->_defaultStateId;
    }
}
