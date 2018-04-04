<style type="text/css">

.gtc-action-summary {
    margin-bottom: 8px;
}

.gtc-action-summary.active {
    cursor: pointer;
}

.gtc-action-description {
    font-style: italic;
}

.gtc-action-show-mini-form-container {
    border-top: 1px;
}

.gtc-action-actions .btn {
    font-size: 8px;
}

.gtc-action-tree.nav-list {
    padding-right: 0px;
    padding-left: 30px;
}

li .gtc-action-result-list-element {
    padding-left: 4px;
    border-left:thick double #999999;
}

</style>
<script type="text/javascript">
<!--
$(".large-modal").fancybox( {
  	'overlayOpacity' : 0.0,
    'showCloseButton' : true,
    'frameHeight' : 700,
    'frameWidth' : 1100,
    'centerOnScroll' : false
});
//-->
</script>

<span class="title"><?php echo xl( 'Case' ) ?> <?php echo $this->vm->getCase()->getCaseId(); ?>: 
<?php echo $this->vm->getCase()->getClient()->getFirstName()." ".$this->vm->getCase()->getClient()->getLastName() ?></span>
<form class="form-horizontal" id="gtc-case-create-form">     
    <div style="display:none;">
        <span id="gtc-client-element"><?php echo $this->vm->getCase()->getClientId() ?></span>
        <span id="gtc-creator-element"><?php echo $this->vm->getCase()->getCreatorId() ?></span>
    </div>
    
    <div id="gtc-owner-element" class="control-group">
        <label class="control-label" for=""><?php echo xl( 'Owners' )?></label>
        <div class="controls well">
            <?php foreach ( $this->vm->getCase()->getOwners() as $owner ) { ?>
            <div>
                <span><?php echo xl('Group').": ".$owner->getGroup(); ?></span>
            </div>
            <?php if ( $owner->getUserId() ) { ?>
            <div>
                <span><?php echo xl('User').": ".$owner->getUsername(); ?></span>
            </div>
            <div class="divider"></div>
            <?php } ?>
            <?php } ?>
        </div>
    </div>
    
    <div id="gtc-general-element" class="control-group">
        <label class="control-label" for=""><?php echo xl( 'General' )?></label>
        <div class="controls well">
            <div class="btn-group pull-left">
                <a href="<?php echo $GLOBALS['webroot'] ?>/interface/patient_file/transaction/transactions.php" class="iframe large-modal btn gtc-log-add" data-case-id="<?php echo $this->vm->getCase()->getCaseId(); ?>"><?php echo xl( 'Log Call' )?></a>
            </div>
        </div>
    </div>
    
    <div id="gtc-actions-element" class="control-group">
        <label class="control-label" for="inputEmail"><?php echo xl( 'Actions' )?></label>
        <div class="controls">
            <div id="gtc-action-tree-container" class="well">
                <?php echo $this->partial( 'action/_tree.php', array( 'actions' => $this->vm->getCase()->getActions(), 'mode' => $this->vm->getMode() ) ); ?>
        	</div>
        </div>
    </div>
    
    <div id="gtc-actions-element" class="control-group">
        <div class="controls well">
            <div class="pull-right">
                <a href="index.php?action=case!edit&caseId=<?php echo $this->vm->getCase()->getCaseId()?>" class='btn btn-warning'><?php echo xl('Edit Case') ?></a>
                <a href="#" class='btn btn-danger'><?php echo xl('Close Case') ?></a>
        	</div>
        </div>
    </div>
</form>
