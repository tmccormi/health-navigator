<?php
require_once( __DIR__."/../../../mi2Lib/php/Mi2/Ui/AbstractForm.php" );
class FormNote extends Mi2_Ui_AbstractForm
{
    protected $_currentUser = array();
    protected $_pid = 0;
    
    public function init()
    {
        $this->_pid = empty($_SESSION['pid']) ? 0 : $_SESSION['pid'];
                
        // set the form name / table name
        $this->setName( 'gtc_note' );	
        
        // add the date only if we're in update mode
        if ( $this->_mode == self::MODE_UPDATE ) {
            $elem = new Zend_Form_Element_Hidden( 'date' );
            $elem->setLabel( 'Date' );
            $this->addElement( $elem );
        }
        
		$elem = new Mi2_Ui_Form_Element_DatePicker( 'reminder_date' );
		$elem->setLabel( 'Reminder Date' )
		    ->setDescription( xl('Add a date to generate a reminder') )
			->setRequired( false );
		$this->addElement( $elem );
		
		$ures = sqlStatement("SELECT username, fname, lname, id FROM users " .
             "WHERE username != '' AND active = 1 AND " .
             "( info IS NULL OR info NOT LIKE '%Inactive%' ) " .
             "ORDER BY lname, fname");
		
		$currentUser = $_SESSION['authUser'];
		$options = array();
	    while ( $urow = sqlFetchArray( $ures ) ) {
	        $title = htmlspecialchars( $urow['lname'], ENT_NOQUOTES );
	        if ( $urow['fname'] ) $title .= htmlspecialchars( ", ".$urow['fname'], ENT_NOQUOTES );
	        $value = htmlspecialchars( $urow['id'], ENT_QUOTES );
	        if ( $urow['username'] == $currentUser ) {
	            $this->_currentUser = $urow;
	        }
	        $options[$value] = $title;
	    }
		
		$elem = new Zend_Form_Element_Select( 'assigned_to' );
		$elem->setLabel( 'To' )
		    ->setRequired( false )
		    ->setMultiOptions( $options )
		    ->setValue( $currentUser );
		$this->addElement( $elem );
			
		$elem = new Zend_Form_Element_Textarea( 'body' );
		$elem->setLabel( 'Note' )
		    ->setAttrib('cols', '80')
		    ->setAttrib('rows', '8');
		$this->addElement( $elem );
    }
    
    public function getTitle()
    {
        return xl( 'GTC Note' );
    }
    
    public function getFormUrl()
    {
        return $GLOBALS['rootdir']."/forms/gtc_note";
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
            if ( isset($params['reminder_date']) && 
                    !empty($params['reminder_date'] ) ) {
                require_once( $GLOBALS['srcdir']."/dated_reminder_functions.php" );
                
                if ( $this->_mode == self::MODE_UPDATE ) {
                   $params['reminder_date'] = str_replace( ' 00:00:00', '', $params['reminder_date'] );
                } 
                sendReminder( 
                        array( $params['assigned_to'] ), 
                        $this->_currentUser['id'], 
                        $params['body'], 
                        $params['reminder_date'], 
                        $this->_pid, 1 );
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
