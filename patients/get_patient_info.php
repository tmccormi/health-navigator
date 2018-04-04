<?php
 // Copyright (C) 2011 Cassian LUP <cassi.lup@gmail.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

    //starting the PHP session (also regenerating the session id to avoid session fixation attacks)
        session_start();
        session_regenerate_id(true);
    //

    //landing page definition -- where to go if something goes wrong
	$landingpage = "index.php?site=".$_SESSION['site_id'];
    //
    
    //checking whether the request comes from index.php
        if (!isset($_SESSION['itsme'])) {
                session_destroy();
		header('Location: '.$landingpage.'&w');
		exit;
	}
    //

    //some validation
        if (!isset($_POST['uname']) || empty($_POST['uname'])) {
                session_destroy();
		header('Location: '.$landingpage.'&w&c');
		exit;
	}
        if (!isset($_POST['code']) || empty($_POST['code'])) {
                session_destroy();
                header('Location: '.$landingpage.'&w&c');
		exit;
        }
    //

    //SANITIZE ALL ESCAPES
    $fake_register_globals=false;

    //STOP FAKE REGISTER GLOBALS
    $sanitize_all_escapes=true;

    //Settings that will override globals.php
        $ignoreAuth = 1;
    //

    //Authentication (and language setting)
	require_once('../interface/globals.php');

        // set the language
        if (!empty($_POST['languageChoice'])) {
                $_SESSION['language_choice'] = $_POST['languageChoice'];
        }
        else if (empty($_SESSION['language_choice'])) {
                // just in case both are empty, then use english
                $_SESSION['language_choice'] = 1;
        }
        else {
                // keep the current session language token
        }

        $authorizedPortal=false; //flag

        $sql = "SELECT * FROM `patient_access_onsite` WHERE `portal_username` = ? AND `portal_pwd` = ?";

		if ($auth = sqlQuery($sql, array($_POST['uname'],$_POST['code']) )) { // if query gets executed
			if (empty($auth)) { // no results found
				session_destroy();
				header('Location: '.$landingpage.'&w');
				exit;
			}	
		} else { // sql error
			session_destroy();
			header('Location: '.$landingpage.'&w');
			exit;
		}

		$sql = "SELECT * FROM `patient_data` WHERE `pid` = ?";

		if ($userData = sqlQuery($sql, array($auth['pid']) )) { // if query gets executed

			if (empty($userData)) {
                                // no records for this pid, so escape
				session_destroy();
                                header('Location: '.$landingpage.'&w');
				exit;
                        }

			if ($userData['allow_patient_portal'] != "YES") {
				// Patient has not authorized portal, so escape
				session_destroy();
                                header('Location: '.$landingpage.'&w');
				exit;
                        }

			if ($auth['pid'] != $userData['pid']) {
				// Not sure if this is even possible, but should escape if this happens
				session_destroy();
				header('Location: '.$landingpage.'&w');
				exit;
			}

			if ($auth['portal_pwd_status'] == 0) {
				if ( isset($_SESSION['password_update']) && !(empty($_POST['code_new'])) && !(empty($_POST['code_new_confirm'])) && ($_POST['code_new'] == $_POST['code_new_confirm']) ) {
					// Update the password and continue (patient is authorized)
					sqlStatement("UPDATE `patient_access_onsite` SET `portal_username`=?,`portal_pwd`=?,portal_pwd_status=1 WHERE pid=?", array($_POST['uname'],$_POST['code_new'],$auth['pid']) );
					$authorizedPortal = true;
				}
				else {
					// Need to enter a new password in the index.php script
					$_SESSION['password_update'] = 1;
                                	header('Location: '.$landingpage);
					exit;
				}
			}

			if ($auth['portal_pwd_status'] == 1) {
				// continue (patient is authorized)
				$authorizedPortal = true;
			}

			if ($authorizedPortal) {
                        	// patient is authorized (prepare the session variables)
				unset($_SESSION['password_update']); // just being safe
				unset($_SESSION['itsme']); // just being safe
				$_SESSION['pid'] = $auth['pid'];
				$_SESSION['patient_portal_onsite'] = 1;
			}
			else {
				session_destroy();
				header('Location: '.$landingpage.'&w');
				exit;
			}

		}
		else { //problem with query
			session_destroy();
			header('Location: '.$landingpage.'&w');
			exit;
		}		
    //

    require_once('summary_pat_portal.php');

?>
