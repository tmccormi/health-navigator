<?php 
require_once( "FormReferral.php" );
function form_gtc_referral_report( $pid, $encounter, $cols, $id ) {
    
    $form = new FormReferral();
    $form->reportAction( $pid, $encounter, $cols, $id );
}
