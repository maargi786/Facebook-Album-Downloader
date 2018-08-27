<?php

    session_start();
    spl_autoload_register();
    require_once('config.php');
    
    //check that album is array or not
    if(gettype($_REQUEST['album'])!="array")
    {
        //if not make album as array
        $albums= array($_REQUEST['album'],);
    }
    else
    {
        //if then store album into albums
        $albums = $_REQUEST["album"];
    }   
    foreach ($albums as $ID) {
        try {
            $a=$ID; //store album id into temporary variable $a
            //fetch the album photos,name,etc.
            $response = $fb->get('/'.$a.'/photos?fields=picture,name,images&limit=100',$_SESSION['fb_access_token']); 
            //fetch all album with there photos
            $getAlbum = $fb->get('/'.$a.'?fields=name,photos.limit(100){images,name,created_time}',$_SESSION['fb_access_token']);
        }catch(FacebookExceptionsFacebookResponseException $e){
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }catch(FacebookExceptionsFacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        //store all album photos as array
        $graphNode = $response->getGraphEdge()->asArray();  

        //if not exits then create temporary directory named 'Download'
        $tmp_dir = __DIR__.'/Download/';
        if (!is_dir($tmp_dir)) {
            mkdir($tmp_dir, 0777);
        }
        
        $albumId = $a; //store value of a into albumId varialbe
        $album = $getAlbum->getGraphNode()->asArray(); //Store all album details as array into album

        //apply various filter on album name and store it into albumName varialbe
        $albumName = $album['name'];
        $albumName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $albumName);
        $albumName = mb_ereg_replace("([\.]{2,})", '', $albumName);
        
        //create directory inside the temp_dir('Download') having album name
        $path = $tmp_dir.$albumName.'/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        //copy the each album photo into the album directory
        foreach ($album['photos'] as $photo) {
            $file = $photo['id'].'.jpg';;
            copy($photo['images'][0]['source'], $path.$file);
        }
    }

    // make album zip
    $zip_name = 'FacebookAlbum.zip'; //default name of album zip
    $zip_directory = '/';
    
    //create object of zip class and pass the two parameter zip name and directory
    $zip = new zip( $zip_name, $zip_directory ); 
    $dir = 'Download'; //set the directory name 'download'
    $zip->add_directory($dir); //add directory to zip file
    $zip->save(); //save the zip instance and close it

    $zip_path = $zip->get_zip_path(); //retrive the zip file path
    
    //zip configuration like type, description, length,etc.
    header( "Pragma: public" );
    header( "Expires: 0" );
    header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
    header( "Cache-Control: public" );
    header( "Content-Description: File Transfer" );
    header( "Content-type: application/zip" );
    header( "Content-Disposition: attachment; filename=\"" . $zip_name . "\"" );
    header( "Content-Transfer-Encoding: binary" );
    header( "Content-Length: " . filesize( $zip_path ) );
    
    //read the zip file
    readfile( $zip_path );
    
    //recursively remove all directory
    $zip->removeRecursive($dir.'/');
?>