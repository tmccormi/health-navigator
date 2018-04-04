<li class="gtc-action-list-element gtc-action-result-list-element">
    <div class="divider"></div>
    <div data-action-id="<?php echo $this->action->getActionId() ?>" data-action-key="<?php echo $this->action->getActionKey() ?>" id="gtc-action-row-<?php echo $this->action->getActionKey() ?>" class="gtc-action-row row">
        <div class="tree-toggler gtc-action-summary <?php echo $this->action->inProgress() ? 'active' : '' ?> span6">
            <div style="display:none;" class="gtc-hidden-miniform">
                <?php echo $this->partial( 'action/_miniform.php', array(
                        'actionTypeOptions' => $this->actionTypeOptions,
                        'actionTypeId' => $this->action->getActionTypeId(),
                        'actionKey' => $this->action->getActionKey(),
                        'parentKey' => $this->action->getParentKey(),
                        'actionId' => $this->action->getActionId(),
                        'parentId' => $this->action->getParentId(),
                        'title' => $this->action->getTitle(),
                        'description' => $this->action->getDescription(),
                        'dueDate' => $this->action->getDueDate(),
                        'actionTypeId' => $this->action->getActionTypeId()
                        ) ) ?>
            </div>
    	    <?php echo $this->partial( 'action/_action.php', array( 'action' => $this->action ) ); ?>
        </div>
        <div class="gtc-action-actions span2 pull-right" style="display: none;">
            <?php if ( $this->mode == Gtc_Case_ViewModel::VIEW ) { ?>
            <div class="btn-group pull-right">
                <?php echo $this->partial( 'action/_progress_toggle.php', array( 'action' => $this->action ) ) ?>
                <button class="btn gtc-note-add" data-case-id="<?php echo $this->action->getCaseId(); ?>" data-action-id="<?php echo $this->action->getActionId(); ?>" ><?php echo xl( 'Note' )?></button>
                <a id="gtc-state-dropdown-toggle" class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <span id="gtc-label-action-<?php echo $this->action->getActionId(); ?>"><?php echo $this->action->getState()->getName(); ?></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php foreach ( $this->action->getLegalStates() as $state ) { ?>
                    <li class="gtc-state" data-id="<?php echo $state->getStateId(); ?>" id="gtc-state-<?php echo $state->getStateId(); ?>">
                        <a href="#" class="gtc-state-change" data-action-id="<?php echo $this->action->getActionId(); ?>" data-state-id="<?php echo $state->getStateId() ?>"><?php echo $state->getName(); ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } else { ?>
            <div class="btn-group pull-right">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <?php echo xl('Action'); ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="gtc-add-sub-action"><a href="#"><?php echo xl('Add Sub-action'); ?></a></li>
                    <li class="gtc-delete-action"><a href="#"><?php echo xl('Delete'); ?></a></li>
                </ul>
            </div>
            <?php } ?> 
        </div>
    </div>
</li>
