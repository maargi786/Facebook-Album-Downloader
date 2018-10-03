<?php

	session_start();
	spl_autoload_register();
	
	//APP_CONFIG-->This is for facebook app(facebook-album-downloader) configuration
	$fb = new Facebook\Facebook([
  				'app_id' => '189382341844324',
	  			'app_secret' => '0da5a6ae294fa18c816c182e9010cbf1',
	  			'default_graph_version' => 'v2.11',
  			]);

    	$helper = $fb->getRedirectLoginHelper();
	$permissions = array('email', 'user_photos'); // provide the permission of user email and photos
	
	//fetch the login url and varios permissions
	$loginUrl = $helper->getLoginUrl('https://patelmargi.azurewebsites.net/FacebookAlbumDownloader/fbcallback.php', $permissions);
	header("location:" . $loginUrl); //redirect the page to login url

?>
