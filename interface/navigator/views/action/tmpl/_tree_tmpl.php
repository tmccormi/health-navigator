<script id="actionTreeTemplate" type="text/x-jQuery-tmpl">
    <?php echo $this->partial( 'action/_tree.php', array( 
            'actionTypeOptions' => $this->actionTypeOptions, 
            'mode' => $this->mode, 
            'actionKey' => '${actionKey}',
            'parentKey' => '${parentKey}',
            'actionId' => '${actionId}', 
            'parentId' => '${parentId}' ) ); ?>
</script>
