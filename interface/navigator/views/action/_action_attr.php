<span class="text"><b><?php echo $this->action->getCase()->getClient()->getFullName() ?></b> </span>
<span class="text">[<?php echo xl('case').' '.$this->action->getCase()->getCaseId() ?>]</span> 
<span class="text-info gtc-action-type-static"><?php echo $this->action->getActionType()->getName(); ?>: </span>
<span class="title"><?php echo $this->action->getTitle(); ?> - <em class="text-warning"><?php echo xl('Due').' '.$this->action->getDueDate(); ?></em></span>
<span class="text-warning gtc-action-state-static"><em><?php echo $this->action->getState()->getName(); ?></em></span>
