<?php
	
	include_once 'core/data/session.php';

	Session::logOut($_SESSION['username']);
	
?>