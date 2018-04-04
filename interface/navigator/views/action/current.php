<table cellspacing="0" cellpadding="1">
	<tr>
		<td>
			<div style='margin-left: 5px; float: left;' id="past_encounter_block">
				<span id="past_encounter" class="title_bar_top">
                    <select id="ActionsInProgress" class="text">
                        <option data-controller="action" data-action="work" value="<?php echo $this->action->getActionId() ?>"><?php echo xl('Active Action') ?></option>
                        <option data-controller="case" data-action="view" value="<?php echo $this->action->getCaseId() ?>"><?php echo xl('View Case') ?></option>
                        <option data-controller="case" data-action="edit" value="<?php echo $this->action->getCaseId() ?>"><?php echo xl('Edit Case') ?></option>
                        <option value="">--</option>
                        <?php foreach ( $this->actionsInProgress as $actionInProgress ) { ?>
                        <option data-controller="action" data-action="work" value="<?php echo $actionInProgress->getActionId() ?>"><?php echo $this->partial( 'action/_action_attr.php', array( 'action' => $actionInProgress ) ) ?></option>
                        <?php } ?>
                    </select>
                </span>
			</div>
		</td>
	</tr>
	<tr>
		<td valign="baseline" align="center">
			<div class='text' id="current_encounter_block">
			    <span class="text"><b><?php echo $this->action->getCase()->getClient()->getFullName() ?></b> </span>
			    <span class="text">[<?php echo xl('case').' '.$this->action->getCase()->getCaseId() ?>]</span>
                <span class="text"><b><?php echo $this->action->getActionType()->getName() ?></b>: </span>
                <span class="text"><i><?php echo $this->action->getTitle() ?></i> - <em class="text-warning"><?php echo xl('Due').' '.$this->action->getDueDate(); ?></em></span>
                <select>
                    <option class="gtc-state" selected value="<?php echo $this->action->getState()->getStateId(); ?>" id="gtc-state-<?php echo $this->action->getState()->getStateId(); ?>">
                        <?php echo $this->action->getState()->getName(); ?>
                    </option>
                    <?php foreach ( $this->action->getLegalStates() as $state ) { ?>
                    <option class="gtc-state" value="<?php echo $state->getStateId(); ?>" id="gtc-state-<?php echo $state->getStateId(); ?>">
                        <?php echo $state->getName(); ?>
                    </option>
                    <?php } ?>
                </select>
			</div>
		</td>
	</tr>
</table>

