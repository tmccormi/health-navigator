<?php
require_once( __DIR__."/../../../mi2Lib/php/Mi2/Ui/AbstractForm.php" );
class FormReferral extends Mi2_Ui_AbstractForm
{
    protected $_currentUser = array();
    protected $_pid = 0;
    
    public function init()
    {
        $this->_pid = empty($_SESSION['pid']) ? 0 : $_SESSION['pid'];
                
        // set the form name / table name
        $this->setName( 'form_gtc_referral' );	
        
        // add the date only if we're in update mode
        if ( $this->_mode == self::MODE_UPDATE ) {
            $elem = new Zend_Form_Element_Hidden( 'date' );
            $elem->setLabel( 'Date' );
            $this->addElement( $elem );
        }
		
		$ures = sqlStatement( "SELECT username, fname, lname, id FROM users " .
             "WHERE active = 1 AND abook_type = ? " .
             "ORDER BY lname, fname", array( 'spe' ) );
		
		$currentUser = $_SESSION['authUser'];
		$options = array();
	    while ( $urow = sqlFetchArray( $ures ) ) {
	        $title = htmlspecialchars( $urow['lname'], ENT_NOQUOTES );
	        if ( $urow['fname'] ) $title .= htmlspecialchars( ", ".$urow['fname'], ENT_NOQUOTES );
	        $value = htmlspecialchars( $urow['id'], ENT_QUOTES );
	        $options[$value] = $title;
	    }
		
		$elem = new Zend_Form_Element_Select( 'provider_id' );
		$elem->setLabel( 'Provider' )
		    ->setRequired( true )
		    ->setMultiOptions( $options );
		$this->addElement( $elem );
		
		$elem = new Zend_Form_Element_Checkbox( 'use_pledge_slot' );
		$elem->setLabel( 'Use Pledge Slot' );
		$this->addElement( $elem );
			
		$elem = new Zend_Form_Element_Textarea( 'note' );
		$elem->setLabel( 'Note' )
		    ->setAttrib('cols', '80')
		    ->setAttrib('rows', '8');
		$this->addElement( $elem );
    }
    
    public function getTitle()
    {
        return xl( 'GTC Referral' );
    }
    
    public function getFormUrl()
    {
        return $GLOBALS['rootdir']."/forms/form_gtc_referral";
    }
    
    public function getScriptPath()
    {
        return __DIR__."/views";
    }
    
    public function onSaveAction()
    {
        $params = $this->request->getParams();
        if ( $this->isValid( $params ) ) {
            if ( isset($params['mode']) ) {
                $this->_mode = $params['mode'];
            }
            if ( isset($params['id']) ) {
                $this->_id = $params['id'];
            }
            
            if ( $this->_mode == self::MODE_NEW &&
		      $params['use_pledge_slot'] == 1 ) {
                // Decrement the provider slots used parameter
                $statement = "UPDATE users SET provider_slots_used = provider_slots_used - 1 WHERE id = ? and provider_slots_used > 0";
                $res = sqlStatement( $statement, array( $params['provider_id'] ) );
            }
            
            $this->save( $params );
            $this->redirect();
            
        } else {
            $this->populate( $params );
        }
        
        $this->setViewScript( 'form.phtml' );
    }
    
    public function onNewAction()
    {
        $this->setViewScript( 'form.phtml' );
    }
    
    public function onReportAction()
    {   
        $this->setViewScript( 'view.phtml' );
    }
    
    public function onViewAction()
    {
        $this->setViewScript( 'form.phtml' );
    }
}
