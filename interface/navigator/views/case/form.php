<head>
<!-- Global Stylesheet -->
<link rel="stylesheet" href="<?php echo $GLOBALS['css_header']; ?>" type="text/css"/>
<link media="all" type="text/css" href="<?php echo css_src( 'jquery-ui.css' ) ?>" rel="stylesheet">
<link media="all" type="text/css" href="<?php echo css_src( 'ui.theme.css' ) ?>" rel="stylesheet">
<link href="<?php echo js_src( 'twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css' ) ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo js_src( 'jquery/1.8.0/jquery.min.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'jquery/ui/1.8.24/jquery-ui.min.js' ) ?>"></script>
<script src="<?php echo js_src( 'twitter-bootstrap/2.3.1/js/bootstrap.min.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'jquery.templates/beta1/jquery.tmpl.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'formHtml.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'case_form.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'navigator.js' ) ?>"></script>

<script type="text/javascript">
var caseForm = new case_form( <?php echo $this->vm->toJson() ?> );
caseForm.init();
</script>

<style type="text/css">
.tree-toggler {
    cursor: pointer;
}

.gtc-action-summary {
    margin-bottom: 8px;
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

#gtc-reference-element input[type="text"] {
    height: 30px;
    line-height: 30px;
}
</style>
<?php echo $this->partial( 'action/tmpl/_tree_tmpl.php', array( 'actionTypeOptions' => $this->vm->getActionTypeOptions() ) ); ?>
<?php echo $this->partial( 'action/tmpl/_row_tmpl.php', array( 'actionTypeOptions' => $this->vm->getActionTypeOptions() )  ); ?>
<?php echo $this->partial( 'action/tmpl/_miniform_tmpl.php', array( 'actionTypeOptions' => $this->vm->getActionTypeOptions() ) ); ?>
</head>
<body class="body_top">   
    <span class="title"><?php echo xl( 'Create/Edit Case' ) ?></span>
    
    <form class="form-horizontal" method="post" action="<?php echo _base_url(); ?>/index.php?action=case!save" id="gtc-case-create-form"> 
        <!-- Save/Cancel buttons -->
        <div id="top_buttons" class="top_buttons">
            <fieldset class="control-group top_buttons">
                <input id="gtc-case-submit-button" type="submit" class="btn btn-primary save" value="<?php xl('Save','e'); ?>" />
                <input type="button" class="btn dontsave" value="<?php xl('Don\'t Save','e'); ?>" />
            </fieldset>
        </div><!-- end top_buttons -->
        
        <div>
            <input id="gtc-case-id-element" type="hidden" name="caseId" value="<?php echo $this->vm->getCase()->getCaseId() ?>"/>
            <input id="gtc-client-element" type="hidden" name="clientId" value="<?php echo $this->vm->getCase()->getClientId() ?>"/>
            <input id="gtc-creator-element" type="hidden" name="creatorId" value="<?php echo $this->vm->getCase()->getCreatorId() ?>"/>
            <?php $count = 0; foreach ( $this->vm->getCase()->getOwners() as $owner ) { ?>
            <input id="gtc-owner-element" type="hidden" name="ownerId[<?php echo $count ?>]" value="<?php echo $owner->getOwnerId() ?>"/>
            <?php $count++; } ?>
        </div>
        
        <div id="gtc-group-element" class="control-group">
            <label class="control-label" for=""><?php echo xl( 'User Group' )?></label>
            <div class="controls">
                <?php echo $this->partial( 'case/_group_options.php', array( 
                        'options' => $this->vm->getGroupOptions(), 
                        'owners' => $this->vm->getCase()->getOwners() ) ); ?>
            </div>
        </div>
    
        
        <div id="gtc-user-element" class="control-group">
            <label class="control-label" for=""><?php echo xl( 'User' )?> (<?php echo xl( 'Optional' )?>)</label>
            <div class="controls" id="gtc-user-options-container">
                <?php echo $this->partial( 'case/_user_options.php', array( 
                        'options' => $this->vm->getUserOptions(), 
                        'owners' => $this->vm->getCase()->getOwners() ) ); ?>
            </div>
        </div>
        
        <div id="gtc-reference-element" class="control-group">
            <label class="control-label" for=""><?php echo xl( 'Reference' )?></label>
            <div class="controls" id="gtc-reference-container">
                <?php echo $this->partial( 'case/_reference_options.php', array( 
                        'options' => $this->vm->getReferenceOptions(), 
                        'reference' => $this->vm->getCase()->getReference(),
                        'referenceCode' => $this->vm->getCase()->getReferenceCode() ) ); ?>
            </div>
        </div>
        
        <div id="gtc-actions-element" class="control-group">
            <label class="control-label" for="inputActions"><?php echo xl( 'Actions' )?></label>
            <div class="controls">
                <div id="gtc-action-tree-container" class="well">
                   <?php if ( count( $this->vm->getCase()->getActions() ) ) { 
                       echo $this->partial( 'action/_tree.php', array( 
                               'actions' => $this->vm->getCase()->getActions(), 
                               'actionTypeOptions' => $this->vm->getActionTypeOptions(),
                               'actionCount' => $this->vm->getCase()->getActionCount() + 1,
                               'actionKey' => 0,
                               'parentKey' => 0,
                               'actionId' => 0,
                               'parentId' => 0,
                               'mode' => $this->vm->getMode() ) );
                    } ?>
            	</div>
            </div>
        </div>
    </form>
</body> 
