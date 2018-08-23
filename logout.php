<?php

	session_start();
	spl_autoload_register();

	session_destroy(); //destroy the session
	header("location:index.php"); //redirect to the index page

?>