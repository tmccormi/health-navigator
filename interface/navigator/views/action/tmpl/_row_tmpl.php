<?php 
    // $actionId, $parentId, $caseId, $actionTypeId, $stateId, $title, $description, $timestamp
    $action = new Gtc_Action( array( 
            'state' => new Gtc_State( array( 'name' => '${state}' ) ),
            'actionType' => new Gtc_Action_ActionType( array( 'name' => '${actionTypeName}' ) ),
            'actionKey' => '${actionKey}',
            'parentKey' => '${parentKey}',
            'actionId' => '${actionId}', 
            'parentId' => '${parentId}',
            'actionTypeId' => '${actionTypeId}',
            'title' => '${title}', 
            'dueDate' => '${dueDate}',
            'description' => '${description}' ) );
?>
<script id="actionRowTemplate" type="text/x-jQuery-tmpl">
<?php echo $this->partial( 'action/_row.php', array( 'action' => $action, 'actionTypeOptions' => $this->actionTypeOptions ) ); ?>
</script>
