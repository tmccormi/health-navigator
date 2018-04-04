<ul class="gtc-action-tree nav nav-list" data-parent-id="<?php echo $this->parentId; ?>" data-parent-key="<?php echo $this->parentKey; ?>">

    <?php if ( count( $this->actions ) > 0 ) { foreach ( $this->actions as $action ) { ?>
        <?php echo $this->partial( 'action/_row.php', array( 
                'actionTypeOptions' => $this->actionTypeOptions, 
                'action' => $action, 
                'mode' => $this->mode ) ); ?>
        <?php if ( count( $action->getChildren() ) > 0 ) { ?>
            <li class="gtc-action-list-element gtc-action-result-list-element">
            <?php echo $this->partial( 'action/_tree.php', array( 
                    'actions' => $action->getChildren(), 
                    'actionTypeOptions' => $this->actionTypeOptions,
                    'mode' => $this->mode,
                    'actionCount' => $this->actionCount + 1,
                    'parentKey' => $action->getActionKey() ) ); ?>
            </li>
        <?php } ?>
    <?php } } ?>
    
    <?php if ( $this->mode != Gtc_Case_ViewModel::VIEW ) { ?>
	<li class="gtc-action-list-element gtc-action-miniform-list-element">
		<?php echo $this->partial( 'action/_miniform_toggle.php', array( 
		        'actionTypeOptions' => $this->actionTypeOptions, 
		        'actionKey' => !empty($this->actionKey) ? $this->actionKey : $this->actionCount,
		        'parentKey' => !empty($this->parentKey) ? $this->parentKey : 0,
		        'actionId' => $this->actionId, 
		        'parentId' => $this->parentId,
		        'toggleOff' => ( count( $this->actions ) > 0 ) ? true : false ) ); ?>
    </li>
    <?php } ?>
</ul>
