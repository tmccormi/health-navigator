<?php
class Gtc_Case_ReferenceType extends Mi2_Adt_AbstractModel
{
    protected $_referenceTypeId = null;
    protected $_name = null;
    protected $_description = null;
    
    public function getReferenceTypeId()
    {
        return $this->_referenceTypeId;
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
