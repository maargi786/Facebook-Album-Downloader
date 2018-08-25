<?php

	session_start();
	spl_autoload_register();
	
	//APP_CONFIG-->This is for facebook app(facebook-album-downloader) configuration
	$fb = new Facebook\Facebook([
  				'app_id' => '__APP ID__',
	  			'app_secret' => '__APP SECRET KEY__',
	  			'default_graph_version' => 'v2.11',
  			]);

    $helper = $fb->getRedirectLoginHelper();
    $helper = $fb->getRedirectLoginHelper();
	$permissions = ['email']; // Optional permissions
	
	//fetch the login url and varios permissions
	$loginUrl = $helper->getLoginUrl('https://patelmargi.azurewebsites.net/FacebookAlbumDownloader/fbcallback.php', $permissions);
	header("location:" . $loginUrl); //redirect the page to login url

?>