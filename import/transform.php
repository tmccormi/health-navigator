<?php
require_once( __DIR__.'/../mi2lib/php/Mi2/ScriptRunner.php' );

class Transform implements Mi2_Behavior_ScriptIF
{
    protected $_infile = null;
    
    public function __construct( $infile ) 
    {
        $this->_infile = $infile;
    }
    
    public function execute()
    {
        // Read json from the file
        $string = file_get_contents( $this->_infile );
        $json = json_decode( $string );
        if ( !$json ) {
            die( "Malformed JSON" );
        }
        $table = $json->table;
        $sql = "";
        if ( $table ) {
            // Do source
            $sourceColArray = array();
            $insertObjects = array();
            
            foreach ( $table->columns as $column ) {
                // If there is a value, ignore the source, and we'll just set the value
                if ( is_object( $column->source ) ) {
                    $insertObjects[$column->destination] = $column->source;
                } else {
                    $sourceColArray[]= $column->source;
                }
                
            }
            
            $count = 0;
            foreach ( $sourceColArray as $sc ) {
               $sourceColumns .= $sc;
               if ( $count < count( $sourceColArray ) - 1 ) {
                   $sourceColumns .= ", ";
               }
               $count++;
           }
            
            // Do destination
            $destinationColumns = "";
            $count = 0;
            foreach ( $table->columns as $column ) {
                $destinationColumns .= $column->destination;
                if ( $count < count( $table->columns ) - 1 ) {
                    $destinationColumns .= ", ";
                }
                $count++;
            }
            
            $select = "SELECT $sourceColumns FROM $table->source";
            $result = sqlStatement( $select );
            while ( $row = sqlFetchArray( $result ) ) {
                $values = "";
                $binds = array();
                $count = 0;
                foreach ( $table->columns as $column ) {
                    if ( isset( $insertObjects[$column->destination] ) ) {
                        $object = $insertObjects[$column->destination];
                        if ( $object->type === "sql" ) {
                            $objRes = sqlQuery( $object->statement );
                            $values .= "?";
                            $binds[]= reset( $objRes );
                        } else if ( $object->type === "constant") {
                            $values .= "?";
                            $binds[]= $object->value;
                        } else {
                            error_log( "Unsupported object type ".$object->type );
                        }
                    } else {
                        $values .= "?";
                        $binds[]= $row[$column->source];
                    }
                    if ( $count < count( $table->columns ) - 1 ) {
                        $values .= ", ";
                    }
                    $count++;
                }
                
                $sql = "INSERT INTO $table->destination ( $destinationColumns ) VALUES ( $values )";
                $ret = sqlStatement( $sql, $binds );
            }
        } 
    }
    
    public function init() 
    {
    }
}

$infile = $argv[1]; // name of the input file
$transform = new Transform( $infile );
$scriptRunner = new Mi2_ScriptRunner();
ini_set( 'max_execution_time', 0 );
ini_set('memory_limit', '-1');
$scriptRunner->execute( $transform );
