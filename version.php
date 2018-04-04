<?php
// Software version identification.
// This is used for display purposes, and also the major/minor/patch
// numbers are stored in the database and used to determine which sql
// upgrade file is the starting point for the next upgrade.
$v_major = '4';
$v_minor = '1';
$v_patch = '2';
$v_tag   = '-dev'; // minor revision number, should be empty for production releases

// A real patch identifier. This is incremented when release a patch for a
// production release. Not the above $v_patch variable is a misnomer and actually
// stores release version information.
$v_realpatch = '0';

// Database version identifier, this is to be incremented whenever there
// is a database change in the course of development.  It is used
// internally to determine when a database upgrade is needed.
//
$v_database = 88;

// Access control version identifier, this is to be incremented whenever there
// is a access control change in the course of development.  It is used
// during installation to determine what the installed version of the access
// controls is (subsequently the acl_upgrade.php script then is used to
// upgrade and track this value)
//
$v_acl = 2;
?>
