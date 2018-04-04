<?php 
$sanitize_all_escapes = true;
$fake_register_globals = false;

html_header_show(); 
?>

<link rel="stylesheet" href="<?php echo $GLOBALS['css_header']; ?>" type="text/css">
<link media="all" type="text/css" href="<?php echo css_src( 'jquery-ui.css' ) ?>" rel="stylesheet">
<link media="all" type="text/css" href="<?php echo css_src( 'ui.theme.css' ) ?>" rel="stylesheet">
<link href="<?php echo js_src( 'twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css' ) ?>" rel="stylesheet">
<style type="text/css">
@import "<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/media/css/demo_page.css";
@import "<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/media/css/demo_table.css";
@import "<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.css";
.mytopdiv { float: left; margin-right: 1em; }
</style>

<script type="text/javascript" src="<?php echo js_src( 'jquery/1.8.0/jquery.min.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'jquery/ui/1.8.24/jquery-ui.min.js' ) ?>"></script>
<script src="<?php echo js_src( 'twitter-bootstrap/2.3.1/js/bootstrap.min.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo js_src( 'navigator.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript">

//Process click to pop up the edit window.
$(document).ready( function() {
	$("#gtc-edit-provider").click( function( e ) {
	    e.preventDefault();
	    top.restoreSession();
	    dlgopen('<?php echo $GLOBALS['webroot'] ?>/interface/usergroup/addrbook_edit.php?userid=' + <?php echo $this->userId; ?>, '_blank', 700, 550);
	});

    $(".large-modal").fancybox( {
      	'overlayOpacity' : 0.0,
        'showCloseButton' : true,
        'frameHeight' : 700,
        'frameWidth' : 1100,
        'centerOnScroll' : false
    });
});

</script>
</head>
<body class="body_top">
    
    <style type="text/css">

    .gtc-action-summary {
        margin-bottom: 8px;
    }
    
    .gtc-action-summary.active {
        cursor: pointer;
    }
    
    .gtc-action-description {
        font-style: italic;
    }
    
    .gtc-action-show-mini-form-container {
        border-top: 1px;
    }
    
    .gtc-action-actions .btn {
        font-size: 8px;
    }
    
    .gtc-action-tree.nav-list {
        padding-right: 0px;
        padding-left: 30px;
    }
    
    li .gtc-action-result-list-element {
        padding-left: 4px;
        border-left:thick double #999999;
    }
    
    </style>

    <span class="title"><?php echo xl( 'Provider' ) ?>: 
    <?php echo $this->firstName." ".$this->lastName ?></span>
    <form class="form-horizontal" id="gtc-case-create-form">     
        <div style="display:none;">
            <span id="gtc-client-element"><?php  ?></span>
            <span id="gtc-creator-element"><?php  ?></span>
        </div>
        
        <div id="gtc-general-element" class="control-group">
            <label class="control-label" for=""><?php echo xl( 'Info' )?></label>
            <div class="controls well">
                <label><?php echo xl('Slots Pledged'); ?></label>
                <div><?php echo $this->slotsPledged; ?></div>
                <label><?php echo xl('Slots Used'); ?></label>
                <div><?php echo $this->slotsUsed; ?></div>
                <label><?php echo xl('Slots Remaining'); ?></label>
                <div><?php echo max( 0, $this->slotsPledged - $this->slotsUsed ); ?></div>
            </div>
        </div>
        
        <div id="gtc-owner-element" class="control-group">
            <label class="control-label" for=""><?php echo xl( 'Clients' )?></label>
            <div class="controls well">
                <table class="table">
                <thead>
                <tr>
                  <th><?php echo xl( 'id' ); ?></th>
                  <th><?php echo xl( 'First Name' ); ?></th>
                  <th><?php echo xl( 'Last Name' ); ?></th>
                  <th><?php echo xl( 'Number Referrals' ); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ( $this->clients as $client ) { ?>
                    <?php if ( $client instanceof Gtc_Client ) { ?>
                    <tr>
                      <td><?php echo $client->getPubpid(); ?></td>
                      <td><?php echo $client->getFirstName(); ?></td>
                      <td><?php echo $client->getLastName(); ?></td>
                      <td><?php echo $this->clientManager->getNumReferrals( $client->getPid(), $this->userId ); ?></td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
                </table>
            </div>
        </div>

        <div id="gtc-actions-element" class="control-group">
            <div class="controls well">
                <div class="pull-right">
                    <a href="#" id="gtc-edit-provider" class='btn btn-warning'><?php echo xl('Edit') ?></a>
            	</div>
            </div>
        </div>
    </form>
        

</body>
