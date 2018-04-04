<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo js_src( 'navigator.js' ) ?>"></script>
<script type="text/javascript">
<!--
$(document).ready( function() {
    parent.left_nav.setPatient(
    		'<?php echo $this->pname ?>',
    		'<?php echo $this->pid ?>',
    	    '<?php echo $this->pubpid ?>',
    	    '',
    	    '<?php echo $this->dob ?>' );	
    	     
    parent.left_nav.setEncounterNoShow(
    		'<?php echo $this->edate ?>',
    		'<?php echo $this->eid ?>',
    		window.name );
    
    loadCurrentAction( <?php echo $this->actionId ?>, function() {
        //top.restoreSession();
        window.location = "<?php echo $this->url ?>";
    });
});
//-->
</script>
