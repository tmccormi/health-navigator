<?php
$fake_register_globals=false;
$sanitize_all_escapes=true;

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', __DIR__ );
}

if (!defined('INTERFACE_PATH')) {
    define('INTERFACE_PATH', __DIR__."/../" );
}

require_once( INTERFACE_PATH."/globals.php" );

if (!defined('MVC_LIBRARY_PATH')) {
    define('MVC_LIBRARY_PATH', $GLOBALS['srcdir'] . '/../mi2lib/php' );
}

if (!defined('LIBRARY_PATH')) {
    define('LIBRARY_PATH', $GLOBALS['srcdir'] );
}

set_include_path(implode(PATH_SEPARATOR, array(
        LIBRARY_PATH, // openEMR library
        MVC_LIBRARY_PATH, // The lib dir that we reference our library classes from
        APPLICATION_PATH . '/include', // application includes
        APPLICATION_PATH . '/library', // the local library
        APPLICATION_PATH,
        get_include_path()
)));

require_once( LIBRARY_PATH."/sql.inc" );
require_once( LIBRARY_PATH."/formatting.inc.php" );
require_once( APPLICATION_PATH."/include/common.php" );

require_once MVC_LIBRARY_PATH.'/Zend/Loader/Autoloader.php';
$autoLoader = Zend_Loader_Autoloader::getInstance();
$autoLoader->setFallbackAutoloader(true);
$autoLoader->registerNamespace('Gtc_');
$bootstrap = new Bootstrap();
$controllerRouter = new Mi2_Mvc_ControllerRouter();
$controllerRouter->route( __DIR__ );
