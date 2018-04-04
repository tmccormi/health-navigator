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

</head>
<body class="body_top">
    <?php echo $this->partial( 'case/_view.php', array( 'vm' => $this->vm, 'mode' => $this->vm->getMode() ) ) ?>
</body>
