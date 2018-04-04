<script id="actionMiniformTemplate" type="text/x-jQuery-tmpl">
<li class="gtc-action-list-element gtc-action-miniform-list-element">
    <?php echo $this->partial( 'action/_miniform_toggle.php', array( 
            'actionTypeOptions' => $this->actionTypeOptions, 
            'actionKey' => '${actionKey}',
            'parentKey' => '${parentKey}',
            'actionId' => '${actionId}', 
            'parentId' => '${parentId}' ) ); ?>
</li>
</script>
    