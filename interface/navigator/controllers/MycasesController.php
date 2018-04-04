<?php
 // Copyright (C) 2010-2011 Aron Racho <aron@mi-squred.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

class MycasesController extends Mi2_Mvc_BaseController 
{   
    protected $dataTable = null;

    public function init()
    {
        $caseManager = new Gtc_Case_CaseManager();
        $sql = $caseManager->getMyCasesSql( Zend_Registry::get( 'activeUser' ) );
        
        $this->dataTable = new Mi2_Ui_DataTable( array( 
                'resultsUrl' => _base_url().'/index.php?action=mycases!results', 
                'tableId' => 'mycases_table', 
                'countSQL' => "SELECT COUNT(*) as count FROM gtc_case",
                'sql' => $sql ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column( 
                array( 'index' => 0, 'width' => '10%', 'title' => xl( 'Case Id' ), 'sName' => 'case_id', 'field' => 'C.case_id' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                array( 'index' => 1, 'width' => '10%', 'title' => xl( 'Client Pub PID' ), 'sName' => 'pubpid', 'field' => 'P.pubpid' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                array( 'index' => 2, 'width' => '10%', 'title' => xl( 'First Name' ), 'sName' => 'fname', 'field' => 'P.fname' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                array( 'index' => 3, 'width' => '10%', 'title' => xl( 'Last Name' ), 'sName' => 'lname', 'field' => 'P.lname' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                 array( 'index' => 4, 'width' => '10%',  'title' => xl( 'Actions' ), 'sName' => 'action_count', 'field' => 'C.action_count', 'searchable' => false ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                 array( 'index' => 5, 'width' => '10%',  'title' => xl( 'Details' ), 'sName' => 'case_id', 'field' => 'C.case_id', 'behavior' => new Mi2_Ui_DataTable_ColumnBehavior_Details( _base_url().'/index.php?action=case!listview' ) ) ) );
        
    }
    
	public function _action_list() 
	{
	    $this->init();
	    $this->view->dataTable = $this->dataTable;
        $this->setViewScript( "mycases/list.php" );
    }
    
    public function _action_results()
    {
        $this->init();
        $this->dataTable->processParams( $this->getRequest()->getParams() );

        // Echo JSON results
        header('Content-type: application/json');
        echo $this->dataTable->getResults();   
    }
    
    public function _action_attach()
    {
        $patientId = $this->getRequest()->getParam( 'pid' );
        $documentId = $this->getRequest()->getParam( 'did' );
        
        // Fetch the document and set the patient ID
        require_once( __DIR__.'/../../../../library/classes/Document.class.php' );
        $d = new Document( $documentId );
        $d->set_foreign_id( $patientId );
        
        
        // must also move the document
        $filePath = $GLOBALS['oer_config']['documents']['repository'].preg_replace("/[^A-Za-z0-9]/","_", $patientId );
        mkdir( $filePath );
        chmod( $filePath, 0777 );
        if ( copy( $d->get_url_filepath(), $filePath.DIRECTORY_SEPARATOR.$d->get_url_file() ) ) {
            unlink( $d->get_url_filepath() );
        } else {
            throw new Exception( "Failed to move document $d->get_url_filepath()" );
        }

        $d->set_url( "file://".$filePath.DIRECTORY_SEPARATOR.$d->get_url_file() );
        $d->persist();
        
        // remove all the matches
        $statement = "DELETE FROM `patient_document_matches` WHERE document_id = ?";
        sqlStatement( $statement, array( $documentId ) );
        
        $this->init();
	    $this->viewBean->dataTable = $this->dataTable;
        $this->setViewScript( "list.php" );
    }
    
    public function _action_delete()
    {
        $documentId = $this->getRequest()->getParam( 'did' );
        
        // Fetch the document and set the patient ID
        require_once( __DIR__.'/../../../../library/classes/Document.class.php' );
        $d = new Document( $documentId );
        // delete the document
        unlink( $d->get_url_filepath() );

        $statement = "DELETE FROM `documents` WHERE id = ?";
        sqlStatement( $statement, array( $documentId ) );
        
        $statement = "DELETE FROM `categories_to_documents` WHERE document_id = ?";
        sqlStatement( $statement, array( $documentId ) );
        
        // remove all the matches
        $statement = "DELETE FROM `patient_document_matches` WHERE document_id = ?";
        sqlStatement( $statement, array( $documentId ) );
        
        $this->init();
        $this->viewBean->dataTable = $this->dataTable;
        $this->setViewScript( "list.php" );
    }
}

