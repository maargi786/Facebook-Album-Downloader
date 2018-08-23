<?php

    session_start();
    spl_autoload_register();
    require_once('config.php');
    require_once('googleconfig.php');

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
            $a=$ID;  //store album id into temporary variable $a
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
        $tmp_dir = __DIR__.'/Upload/';
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
            $file = $photo['id'].'.jpg';
            // echo "<br>PATH:".$path.$file;
            copy($photo['images'][0]['source'], $path.$file);
        }
    }
    
    $files = array(); //create files as array
    $dir = dir('Upload/'); //fetch the files or folder from 'Upload' directory

    //makethe array of each directory
    while ($file = $dir->read())
    {
        if ($file != '.' && $file != '..')
        {
            $files[] = $file;
        }
    }
    $dir->close();//close directory

    //set accesstoken to client
    $client->setAccessToken($_SESSION['accessToken']);

    //set folder type and name of folder
    $folder_mime = "application/vnd.google-apps.folder";
    $folder_name = 'Facebook_'.$user['name'].'_Albums';
    $service = new Google_DriveService($client);
    $folder = new Google_DriveFile();

    //insert folder
    $folder->setTitle($folder_name);
    $folder->setMimeType($folder_mime);
    $newFolder = $service->files->insert($folder);

    //take the id of folder as parent id
    $parentId  = $newFolder['id'];

    //fetch the photos from folder
    foreach ($files as $file_name)
    {
        $file_path = 'Upload/'.$file_name;
        if(is_dir($file_path))
        {
            //create subfolder with in the parent folder
            $folder_mime = "application/vnd.google-apps.folder";
            $folder_name = $file_name;
            $service = new Google_DriveService($client);
            $folder = new Google_DriveFile();

            //set title and type of folder
            $folder->setTitle($folder_name);
            $folder->setMimeType($folder_mime);
            
            //set the parent id as parent referance of sub folder
            $parent = new Google_ParentReference();
            $parent->setId($parentId);          
            $folder->setParents(array($parent));

            //insert sub folders
            $newFolder = $service->files->insert($folder);
            //fetch the id
            $folderId  = $newFolder['id'];
        }

        $picture = array(); //take picture as array
        $dir = dir($file_path.'/'); //set the directory path to album photos
        //store all the photos of album in picture array
        while ($file = $dir->read())
        {
            if ($file != '.' && $file != '..')
            {
                $picture[] = $file;
            }
        }
        $dir->close(); //closedirectory
 
        //upload file content
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $service = new Google_DriveService($client);
        $file = new Google_DriveFile();

        //set the parent folder of album photos
        if ($folderId != null) {
            $folder = new Google_ParentReference();
            $folder->setId($folderId);
            $file->setParents(array($folder));
        }
        //upload the photos of album to google drive
        foreach ($picture as $file_name)
        {
            $file_path1 = $file_path.'/'.$file_name; //set path
            $mime_type = finfo_file($finfo, $file_path1); // set the mine type of photo
            $file->setTitle($file_name); //set title of photo
            $file->setDescription('This is a '.$mime_type.' document'); //set description of photo
            $file->setMimeType($mime_type); //set mine type
            //insert photo in to google drive 
            $service->files->insert(
                    $file,
                    array(
                        'data' => file_get_contents($file_path1),
                        'mimeType' => $mime_type
                    )
                );
        }
        finfo_close($finfo); //close the file

    }

    function removeRecursive($dir)
    {
        // Remove . and .. firectories from the directory list
        $files = array_diff(scandir($dir), array('.','..'));

        // Delete all files one by one
        foreach ($files as $file) {
            // If current file is directory then recurse it
            (is_dir("$dir/$file")) ? removeRecursive("$dir/$file") : unlink("$dir/$file");
        }

        // Remove blank directory after deleting all files
        return rmdir($dir);
    }
    removeRecursive($path); //call function that remove all the directory

    header('location: fbalbum.php'); //redirect to the fbalbum.php page
?>