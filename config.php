<?php
	
	spl_autoload_register();

	//APP_CONFIG This is for facebook-album-downloader app
    $fb = new Facebook\Facebook([
  				'app_id' => '__APP ID__',
	  			'app_secret' => '__APP SECRET KEY__',
	  			'default_graph_version' => 'v2.11',
  			]);

    $helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // Optional permissions
	//login url
	$loginUrl = $helper->getLoginUrl('https://patelmargi.azurewebsites.net/FacebookAlbumDownloader/fbcallback.php', $permissions);
	//logout url
	$logoutUrl = $helper->getLogoutUrl($_SESSION['fb_access_token'],'https://patelmargi.azurewebsites.net/FacebookAlbumDownloader/logout.php');

	//header("location:" . $loginUrl);


	//USER DETAIL + AVTAR 
	//to retrive users's information like id, name, etc. 
    try {
	  $response = $fb->get('/me?fields=id,name,cover,gender,email,picture,link', $_SESSION['fb_access_token']);
	  $responseImg = $fb->get('/me/picture?redirect=false', $_SESSION['fb_access_token']);
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  echo 'Graph returned an error111: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}
	$user = $response->getGraphUser();
	$img = $responseImg->getGraphUser();

	//to retrive albums details like name, total photoes, cover photo id
	try {
  	// Returns a `FacebookFacebookResponse` object
  	$response = $fb->get('/me/albums?fields=name,count,cover_photo{id}',$_SESSION['fb_access_token']);
	} catch(FacebookExceptionsFacebookResponseException $e) {
  	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
	} catch(FacebookExceptionsFacebookSDKException $e) {
  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
	}
	$graphNode = $response->getGraphEdge()->asArray();

	//getCover function is used to fetch the cover photo of album
	function getCover($albumId)
	{
		$fb = new Facebook\Facebook([
  				'app_id' => '__APP ID__',
	  			'app_secret' => '__APP SECRET KEY__',
	  			'default_graph_version' => 'v2.11',
  			]);
		try {
            $response = $fb->get('/'.$albumId.'?fields=images',$_SESSION['fb_access_token']);
            return end($response->getGraphNode()->asArray()['images'])['source'];
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return false;
	}
	
?>