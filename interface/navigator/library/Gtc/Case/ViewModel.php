<?php
class Gtc_Case_ViewModel extends Mi2_Ui_BaseModel
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    
    protected $_case = null;
    protected $_mode = null;
    protected $_userOptions = array();
    protected $_groupOptions = array();
    protected $_actionTypeOptions = array();
    protected $_referenceOptions = array();
    protected $_count = 0;
    
    public function __construct( array $options = null )
    {
        parent::__construct( $options );
        $this->_count = $this->getCase()->getActionCount();
    }
    
    public function toJson()
    {
        $json = array(
                'actionCount' => $this->_case->getActionCount(),
                'mode' => $this->getMode() );
        
        return Zend_Json::encode( $json );
    }
    
    public function getCase()
    {
        return $this->_case;
    }
    
    public function getMode()
    {
        return $this->_mode;
    }
    
    public function getUserOptions()
    {
        return $this->_userOptions;
    }
    
    public function getGroupOptions()
    {
        return $this->_groupOptions;
    }
    
    public function getActionTypeOptions()
    {
        return $this->_actionTypeOptions;
    }
    
    public function getReferenceOptions()
    {
        return $this->_referenceOptions;
    }
}
