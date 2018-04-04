<?php
    $class = "";
    $value = "";
    $text = "";
    if ( $this->action->inProgress() == true ) {
        $class = "active";
        $value = "stop-progress";
        $text = "Stop Progress";
    } else {
        $value = "start-progress";
        $text = "Start Progress";
    }
?>
<button type="button" data-toggle="button" class="gtc-progress-button btn <?php echo $class ?>" data-value="<?php echo $value ?>" data-loading-text="Loading ......"><?php echo xl( $text ); ?></button>
