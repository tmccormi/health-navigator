<?php
require_once( __DIR__."/../../../mi2Lib/php/Mi2/Ui/AbstractForm.php" );
class FormProviderCheck extends Mi2_Ui_AbstractForm
{
    public function init()
    {
        $this->setName( 'gtc_provider_check' );	
	
		$street = new Zend_Form_Element_Text( 'street' );
		$street->setLabel( 'Street' )
			->setRequired();
		$this->addElement( $street );
			
		$city = new Zend_Form_Element_Text( 'city' );
		$city->setLabel( 'City' )
			->setRequired();
		$this->addElement( $city );
		
		$state = new Zend_Form_Element_Text( 'state' );
		$state->setLabel( 'State' )
			->setDescription( 'two letter state code' )
			->setRequired()
			->addValidator( 'StringLength', false, array( 2, 2 ) );
		$this->addElement( $state );
		
		$zip = new Zend_Form_Element_Text( 'zip' );
		$zip->setLabel( 'Zip' )
			->setDescription( '5-digit zip code' )
			->setRequired()
			->addErrorMessage( 'invalid 5-digit zip code' )
			->addValidator( 'alnum', false )
			->addValidator( 'StringLength', false, array( 5, 5 ) );
		$this->addElement( $zip );
    }
    
    public function getFormUrl()
    {
        return $GLOBALS['rootdir']."/forms/gtc_provider_check";
    }
    
    public function getScriptPath()
    {
        return __DIR__."/views";
    }
    
    public function onSaveAction()
    {
        $params = $this->request->getParams();
        if ( $this->isValid( $params ) ) {
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
        $this->setViewScript( 'form.phtml' );
    }
    
    public function onViewAction()
    {
        $this->setViewScript( 'form.phtml' );
    }
}
