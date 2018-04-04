<select class="req" id="gtc-group-select" name="group">
    <?php foreach ( $this->options as $key => $value ) { ?>
    <option 
    <?php foreach ( $this->owners as $owner ) { 
       if ( $owner->getGroup() == $value ) echo 'selected'; 
    } ?> 
    value="<?php echo $value; ?>"><?php echo $key; ?></option>
    <?php } ?>
</select>
