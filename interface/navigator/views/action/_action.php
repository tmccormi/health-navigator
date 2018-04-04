<div id="gtc-action-<?php echo $this->action->getActionKey() ?>" data-action-id="<?php echo $this->action->getActionId() ?>" data-action-key="<?php echo $this->action->getActionKey() ?>" class="gtc-action">   
    <div class="nav-header">
        <h5><?php echo $this->action->getTitle(); ?> <em class="text-warning"><?php echo xl('Due').' '.$this->action->getDueDate() ?></em></h5>
        <span class="text-info gtc-action-type-static"><?php echo $this->action->getActionType()->getName(); ?></span>
        <span class="text-warning gtc-action-state-static"><em><?php echo $this->action->getState()->getName(); ?></em></span>
        <span <?php echo $this->action->inProgress() == true ? '' : 'style="display:none;' ?> class="muted gtc-action-status"><em><?php echo xl( 'In Progress') ?></em></span>
    </div>
    <div class="gtc-action-description"><?php echo $this->action->getDescription(); ?></div>
</div>
 