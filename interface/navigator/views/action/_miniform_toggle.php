<div class="gtc-action-show-mini-form-container controls-row">
    <div class="gtc-action-show-mini-form form-toggle" <?php echo ( $this->toggleOff ) ? '' : 'style="display:none;"' ?> >
        <a href="javascript;"><?php echo xl('Add')." ".xl('Action')?></a>
    </div>
    <div class="gtc-action-mini-form form-toggle" <?php echo ( $this->toggleOff ) ? 'style="display:none;"' : '' ?>>
        <?php echo $this->partial( 'action/_miniform.php', array( 
                'actionTypeOptions' => $this->actionTypeOptions, 
                'actionKey' => $this->actionKey,
                'parentKey' => $this->parentKey,
                'actionId' => $this->actionId, 
                'parentId' => $this->parentId ) ); ?>
                
                    <script type="text/javascript">
    <!--
    <?php 
    $dateFormat = 'yy-mm-dd'; 
    if ( $GLOBALS['date_display_format'] == 1 ) {
        $dateFormat = 'mm/dd/yy';
    } else if ( $GLOBALS['date_display_format'] == 2 ) {
        $dateFormat = 'dd/mm/yy';
    }
    ?>
        $(document).ready( function() {
            $(".date-picker").datepicker( { dateFormat: '<?php echo $dateFormat ?>' } );
        });
    -->
    </script>
    </div>
</div>
