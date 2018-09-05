<?php

    session_start();
    require_once('config.php');
    
    if(isset($_POST['ID']))
    {
      $albumId=$_POST['ID']; //fetch the album id using get
      $albums = $graphNode;
    }
    //find the album name from album id
    foreach ($albums as $album) {
      if($album['id'] == $_POST['ID'])
        {
          $albumName = $album['name'];//fetch the album name
          $count = $album['count']; //total no. of photos in album
        }
    }
    //echo $albumName;
    try {
      //fetch all the photoes of album has id as albumid
      $response = $fb->get('/'.$albumId.'/photos?fields=picture,images,name,created_time&limit=100',$_SESSION['fb_access_token']);
    } catch(FacebookExceptionsFacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(FacebookExceptionsFacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
    $graphNode = $response->getGraphEdge()->asArray();
    // print_r($graphNode);

?>


<div class="modal-content-slider">
    <!-- sclider close button code -->
    <span class="closeSlider" onclick="document.getElementById('myModalSlider').style.display='none'">&times;</span>
    <div class="slidercontainer">
      <div class="col">
        <!-- sclider indicator code -->
        <div id="carouselExampleIndicators" class="carousel slide">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <?php foreach ($graphNode as $album) { ?>
            <li data-target="#carouselExampleIndicators" data-slide-to="1" class="active"></li>
            <?php } ?>
          </ol>

          <!-- album name slide -->
          <div class="carousel-inner">
            <div class="carousel carousel-item active">
              <div class="carousel-caption d-md-block" style="top:27%;">
                <h3 class="icon-container" data-animation= "animated bounceInDown" style="color: red;">
                  <span class="fa fa-heart"></span>
                </h3>
                <h1 data-animation="animated fadeInUp">
                  <!-- finding first name of facebook user -->
                  <?php $parts = explode(' ', $user['name']);
                        $name_first = array_shift($parts);
                        echo $name_first;
                  ?>'s
                </h1>
                <h3 data-animation="animated fadeInUp">
                  <!-- display album name -->
                  Album of <?php echo $albumName; ?>
                </h3>
                <h4 data-animation="animated zoomInUp">
                  <!-- display the total no of imags in the album -->
                  <?php echo $count; ?> Photos
                </h4>
              </div>
            </div>

            <!-- album slide -->
            <!-- take single album details as album for displaying photoes in slide show -->
            <?php foreach ($graphNode as $album) { ?>
            <div class="carousel carousel-item">
              <!-- display the album image -->
              <img src="<?php echo $album['images'][0]['source']; ?>" height="100px" width="110px" />
              <div class="carousel-caption d-md-block imagecaption">
                <!-- display the caption of album if any-->
                <?php if (isset($album['name'])) {?>
                <h3 data-animation="animated fadeInUp"><?php echo $album['name']; ?></h3>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
          </div>

          <!-- next-previous controls -->
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        
      </div>
    </div>
  </div>

