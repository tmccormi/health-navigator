<?php 
require_once( "FormNote.php" );



function gtc_note_report( $pid, $encounter, $cols, $id ) {
    
    $form = new FormNote();
    $form->reportAction( $pid, $encounter, $cols, $id );
}
