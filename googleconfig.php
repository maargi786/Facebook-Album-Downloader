<?php
    session_start();
    spl_autoload_register();
    require_once('config.php');

    //find the host url from whole url
    $url_array = explode('?', 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    $url = $url_array[0];

    require_once 'Google/Google_Client.php';
    require_once 'Google/contrib/Google_DriveService.php';

    //create object of google client and set various property like client id, secret, redirecturi, etc.
    $client = new Google_Client();
    $client->setClientId('__GOOGLE APP ID__'); 
    $client->setClientSecret('__GOOGLE APP SECRET KEY__');
    $client->setRedirectUri($url);
    $client->setScopes(array('https://www.googleapis.com/auth/drive'));
    
    //if user aunthenticate the create session with accesstoken
    if (isset($_GET['code']))
    {
        $_SESSION['accessToken'] = $client->authenticate($_GET['code']); //create and store session value
        header('location:fbalbum.php'); //redirect to fbalbum.php
        exit;
    }
    elseif (!isset($_SESSION['accessToken']))
    {
        $client->authenticate(); //if access token is not set then authenticate the user
    }

?>