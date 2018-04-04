<select id="gtc-user-select" name="userId">
    <option value="">--</option>
    <?php foreach ( $this->options as $key => $value ) { ?>
    <option 
    <?php foreach ( $this->owners as $owner ) { 
       if ( $owner->getUserId() == $value ) echo 'selected'; 
    } ?> 
    value="<?php echo $value; ?>"><?php echo $key; ?></option>
    <?php } ?>
</select>
