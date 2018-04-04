<?php
require_once( __DIR__."/../../../mi2Lib/php/Mi2/Ui/AbstractForm.php" );
class FormEligibilityCheck extends Mi2_Ui_AbstractForm
{
    public function init()
    {
        $this->setName( 'gtc_eligibility_check' );	
	
		$elem = new Zend_Form_Element_Checkbox( 'eligible' );
		$elem->setLabel( 'Eligible' );
		$this->addElement( $elem );	
			
		$elem = new Zend_Form_Element_Textarea( 'note' );
		$elem->setLabel( 'Note' )
		    ->setAttrib('cols', '80')
		    ->setAttrib('rows', '8');
		$this->addElement( $elem );
    }
    
    public function getFormUrl()
    {
        return $GLOBALS['rootdir']."/forms/gtc_eligibility_check";
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
