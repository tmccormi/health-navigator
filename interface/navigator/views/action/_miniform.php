<div class="gtc-action-miniform">
    <div>
        <input class="gtc-action-miniform-param" type="hidden" data-name="actionKey" name="actionKey[<?php echo $this->actionKey; ?>]" value="<?php echo $this->actionKey; ?>"/>
        <input class="gtc-action-miniform-param" type="hidden" data-name="parentKey" name="parentKey[<?php echo $this->actionKey; ?>]" value="<?php echo $this->parentKey; ?>"/>  
        <input class="gtc-action-miniform-param" type="hidden" data-name="actionId" name="actionId[<?php echo $this->actionId; ?>]" value="<?php echo $this->actionId; ?>"/>
        <input class="gtc-action-miniform-param" type="hidden" data-name="parentId" name="parentId[<?php echo $this->actionId; ?>]" value="<?php echo $this->parentId; ?>"/>
    </div>
    <div>
        <select class="gtc-action-type-select gtc-action-miniform-param req" data-name="actionTypeId" name="actionTypeId[<?php echo $this->actionKey; ?>]" style="width:220x;">
            <?php $actionTypeName = ""; $count = 0; foreach ( $this->actionTypeOptions as $key => $value ) { ?>
            <?php if ( $count == 0 ) $actionTypeName = $key; ?>
            <option <?php echo ($value == $this->actionTypeId) ? 'selected' : '' ?>value="<?php echo $value ?>"><?php echo $key ?></option>
            <?php $count++; } 
            ?>
        </select>
        <input class="selected-action-type-name gtc-action-miniform-param" type="hidden" data-name="actionTypeName" name="actionTypeName[<?php echo $this->actionKey; ?>]" value="<?php echo $actionTypeName ?>">
        <input class="gtc-action-miniform-param req" data-name="title" name="title[<?php echo $this->actionKey; ?>]" value="<?php echo $this->title ?>" style="width:210px; height:30px;" class="span2" type="text" placeholder="title...">
        <input class="gtc-action-miniform-param date-picker" data-name="dueDate" name="dueDate[<?php echo $this->actionKey; ?>]" value="<?php echo $this->dueDate ?>" style="width:210px; height:30px;" class="span2" type="text" placeholder="due date...">
    </div><br>
    <div>
        <textarea style="width: 648px; height: 68px;" class="gtc-action-miniform-param" data-name="description" name="description[<?php echo $this->actionKey; ?>]" placeholder="description..." rows="3" cols="10" style="width:432px;"><?php echo $this->description ?></textarea>
    </div><br>
    <div>
        <button class="gtc-add-action-button btn btn-success" type="button"><?php echo xl('Add')." ".xl('Action') ?></button>
        <button class="btn cancel" type="button"><?php echo xl('Cancel') ?></button>
    </div>
</div>
