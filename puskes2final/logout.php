<?php 

	session_start();

		if ($_SESSION['login'] == true) {

            unset($_SESSION['login']);

        }elseif($_SESSION['loginDokter'] == true){

            unset($_SESSION['loginDokter']);

        }elseif($_SESSION['loginPasien'] = true){

            unset($_SESSION['loginPasien']);

        } else {

            header("Location: login.php");
	    	exit;

        }

	header("Location: login.php");
	exit; 

?>