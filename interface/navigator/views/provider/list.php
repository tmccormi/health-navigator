<?php 
$sanitize_all_escapes = true;
$fake_register_globals = false;
?>
<head>
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
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
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.js"></script>

<!-- this is a 3rd party script -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/extras/ColReorder/media/js/ColReorderWithResize.js"></script>
<script type="text/javascript" src="<?php echo js_src( 'data_table.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo js_src( 'navigator.js' ) ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript">
<!--
var data_table = new data_table( <?php echo $this->dataTable->toJson() ?>);
data_table.init();
//-->
</script>

</head>
<body class="body_top">
    <table class="display formtable" id="<?php echo $this->dataTable->getTableId() ?>">
    	<thead>
    		<tr>
    		    <?php foreach ( $this->dataTable->getColumns() as $ch ) { ?>
    			    <th style="width:<?php echo $ch->getWidth(); ?>;"><?php echo $ch->getTitle(); ?></th>
    			<?php } ?>
    		</tr>
    	</thead>
    	<tbody>
    	</tbody>
    </table>
</body>

<script type="text/javascript">
<!--
$(document).ready( function() {
    $(".fancybox").fancybox();
});
//-->
</script>
