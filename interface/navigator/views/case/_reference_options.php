<select id="gtc-user-select" name="reference">
    <option value="">--</option>
    <?php foreach ( $this->options as $key => $value ) { ?>
    <option 
        <?php if ( $this->reference == $value ) { echo 'selected'; } ?> 
        value="<?php echo $value; ?>"><?php echo $key; ?>
    </option>
    <?php } ?>
</select>
<input type="text" id="referenceCode" name="referenceCode"/>
