<?php

class ProvidersController extends Mi2_Mvc_BaseController 
{   
    protected $dataTable = null;

    public function init()
    {
        $sql = new Mi2_Db_Sql(); 
        $statement = "SELECT U.id, U.fname, U.lname, U.abook_type, U.specialty " .
                "FROM users U WHERE abook_type = ?"; 
        $sql->query( $statement, array( 'spe' ) );
        $this->dataTable = new Mi2_Ui_DataTable( array( 
                'resultsUrl' => _base_url().'/index.php?action=providers!results', 
                'tableId' => 'providers_table', 
                'countSQL' => "SELECT COUNT(*) as count FROM users WHERE abook_type = 'spe'",
                'sql' => $sql ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column( 
                array( 'index' => 0, 'width' => '10%', 'title' => xl( 'User Id' ), 'sName' => 'id', 'field' => 'U.id' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                array( 'index' => 1, 'width' => '10%', 'title' => xl( 'First Name' ), 'sName' => 'fname', 'field' => 'U.fname' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                array( 'index' => 2, 'width' => '10%', 'title' => xl( 'Last Name' ), 'sName' => 'lname', 'field' => 'U.lname' ) ) );
        $this->dataTable->addColumn( new Mi2_Ui_DataTable_Column(
                array( 'index' => 3, 'width' => '10%',  'title' => xl( 'Details' ), 'sName' => 'id', 'field' => 'U.id', 'behavior' => new Mi2_Ui_DataTable_ColumnBehavior_Details( _base_url().'/index.php?action=provider!view' ) ) ) );
        
    }
    
	public function _action_list() 
	{
	    $this->init();
	    $this->view->dataTable = $this->dataTable;
        $this->setViewScript( "provider/list.php" );
    }
    
    public function _action_results()
    {
        $this->init();
        $this->dataTable->processParams( $this->getRequest()->getParams() );

        // Echo JSON results
        header('Content-type: application/json');
        echo $this->dataTable->getResults();   
    }
}

