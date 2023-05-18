<?php 
	include './config/connection.php';

	$gotoPage = $_GET['goto_page']."?";

	$message = $_GET['message'];

	$rec = '';
	if (isset($_GET['recover'])) {
		$rec = $_GET['recover'];
		$gotoPage = "trash.php?recover=".$rec."&";

		header("Location:".$gotoPage."message=$message");
		exit;
	}
     	
  	header("Location:".$gotoPage."message=$message");

?>
