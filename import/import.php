<?php
require_once( __DIR__.'/../mi2lib/php/Mi2/ScriptRunner.php' );

class Import implements Mi2_Behavior_ScriptIF
{
    protected $_infile = null;
    protected $_table = null;
    protected $_columns = array();
    
    public function __construct( $infile, $table ) 
    {
        $this->_infile = $infile;
        $this->_table = $table;
    }
    
    protected function sanitizeKey( $string )
    {
        $sanitized = str_replace( ".", "", $string );
        $sanitized = str_replace( " ", "_", $sanitized );
        $sanitized = str_replace( ",", "_", $sanitized );
        $sanitized = str_replace( "/", "_", $sanitized );
        $sanitized = str_replace( "\\", "_", $sanitized );
        $sanitized = str_replace( "?", "", $sanitized );
        
        return $sanitized;
    }
    
    protected function sanitizeValue( $string )
    {
        $sanitized = str_replace( ",", " ", $string );
        return $sanitized;
    }
    
    public function execute()
    {
        // Read the data and column names into memory
        $data = $this->read_csv( $this->_infile );
        
        // Create the table if it doesn't exist
        $count = 0;
        $colnames = "";
        foreach ( $this->_columns as $column ) {
            $column = $this->sanitizeKey( $column );
            $colnames .= $column." TEXT ";
            if ( $count < count($this->_columns) - 1 ) {
                $colnames .= ", ";
            }
            $count++;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->_table." ( ".$colnames." ) ";
        $ret = sqlStatement( $sql );
        
        // Populate the table with data from the csv file
        foreach ( $data as $row ) {
            $keys = "";
            $values = "";
            $binds = array();
            $count = 0;
            foreach ( $row as $key => $value ) {
                $value = $this->sanitizeValue( $value );
                $keys .= $this->sanitizeKey( $key );
                $values .= "?";
                $binds[]= $value;
                if ( $count < count($row) - 1 ) {
                    $keys .= ", ";
                    $values .= ", ";
                }
                $count++;
            }
            $sql = "INSERT INTO ".$this->_table." ( ".$keys." ) VALUES ( ".$values." ) ";
            
            $ret = sqlStatement( $sql, $binds );
        }
    }
    
    protected function read_csv( $file ) 
    {
        $content = array();
        if ( ( $handle = fopen( $file, "r" ) ) !== false ) {
            $columns = fgetcsv( $handle, 5000, "|" );
            $this->_columns = $columns;
            while ( $row = fgetcsv( $handle, 5000, "|" ) ) {
                for ( $i=0; $i < count($columns); $i++ ) {
                    // echo "attr[".$columns[$i]."] = ".$row[$i]."".$line_break;
                    $attr[$columns[$i]] = $row[$i];
                }
                $content[]= $attr;
                // echo "---------------------------------".$line_break;
            }
            fclose( $handle );
            //unlink( $file );
        } else {
            die( "error trying to open ".$file  );
        }
         
        return $content;
    }
    
    public function init() 
    {
    }
}

$infile = $argv[1]; // name of the input file
$table = $argv[2]; // name of the table to import into, must have col definitions
$import = new Import( $infile, $table );
$scriptRunner = new Mi2_ScriptRunner();
ini_set( 'max_execution_time', 0 );
ini_set('memory_limit', '-1');
$scriptRunner->execute( $import );
