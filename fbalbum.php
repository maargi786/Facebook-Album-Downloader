<?php
    session_start();
    require_once('config.php');
    
    $albums = []; //for getting album details
    $albums = $graphNode; //store graphnode as albums
    $albumVar = []; // for single album details
    $albumIds = ''; // album ids

    //condition check that albums are empty or not
    if(!empty($albums)){
      //each album as $album
      foreach ($albums as $album) {
        //album id sperated by ','
        $albumIds .= $album['id'].',';
        // get the album coverphotoes and store it to albumVar
        $album['cover_photo'] = getCover($album['cover_photo']['id']); 
        $albumVar[] = $album;
      }
    } else{
      echo "<script type='text/javascript'>alert('Please, Read the instruction carefully and Provide the accessiblity of your facebook albums!!');</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Facebook Album Downloader</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">

  <!-- javascript for audio when user click on download icon -->
  <script type="text/javascript">
    var beep = new Audio();
    beep.src = "sound/beep.mp3";
  </script>

  <style type="text/css">    
    #child {
        display: none;
    }
  </style>
</head>

<body>
  <!--==========================
    Header
  ============================-->
  <form method="post" name="form_album" id="form_album" >
    <header id="header">
      <div class="container-fluid">
        <div id="logo" class="pull-left">
          <h1><a class="scrollto">FaceBook Album</a></h1>
        </div>
        <nav id="nav-menu-container">
          <ul class="nav-menu">
            <li class="menu-has-children"><a style="color:white; cursor: pointer;">Download</a>
              <ul>
                <li><a id="select_download_all" name="select_download_all" style="cursor: pointer;">All</a></li>
                <li><a id="select_download" name="select_download" style="cursor: pointer;">Selected</a></li>
              </ul>
            </li>
            <li class="menu-has-children"><a style="color:white; cursor: pointer;">Move to Drive</a>
              <ul>
                <li><a id="select_upload_all" name="select_upload_all" style="cursor: pointer;">All</a></li>
                <li><a id="select_upload" name="select_upload" style="cursor: pointer;">Selected</a></li>
              </ul>
            </li>
            <li class="menu-has-children"><a style="color:white; cursor: pointer;"><?php echo $user['name'] ?></a>
              <ul>
                <li><a href="<?php echo $logoutUrl; ?>">Log Out</a></li>
              </ul>
            </li>
          </ul>
        </nav><!-- #nav-menu-container -->
      </div>
    </header><!-- #header -->

    <!--========================== 
      Portfolio Section
    ============================-->
    <section id="portfolio"  class="section-bg" >
      <div class="container">
        <header class="section-header">
          <h3 class="section-title">
            <!--User's First Name alnog with album-->
            <?php
              $parts = explode(' ', $user['name']);
              $name_first = array_shift($parts);
              echo $name_first;
            ?>'s Album
          </h3>
        </header>
        <div class="row portfolio-container">
          <!-- Diplay each album of user's -->
          <?php foreach ($albumVar as $value) { ?> <!-- foreach loop start -->
          <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
            <div class="portfolio-wrap">
              <figure>
                <!-- Display the cover photo of album -->
                <img src="<?php echo $value["cover_photo"] ?>" 
                      style="height:100%; width: 100%" 
                      class="img-fluid" 
                      alt="">
                <!-- User's album sclide shows -->
                <a onclick="window.open('fbalbum_slider.php?id=<?php echo $value['id']; ?>','_blank','toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=750,height=550')"  
                      data-title="App 1" 
                      style="cursor: pointer;"
                      class="link-preview" 
                      title="preview album">
                    <i class="ion ion-eye"></i>
                </a>
                <!-- Download link with album id -->
                <a href="downloadalbum.php?album=<?php echo $value['id']; ?>"
                      onmousedown="beep.play()"
                      class="link-download" 
                      title="download album">
                    <i class="ion ion-archive"></i>
                </a>
                <!-- Upload album link with album id -->
                <a href="uploadalbum.php?album=<?php echo $value['id']; ?>"
                      class="link-cloud" 
                      title="upload album to google drive">
                    <i class="ion ion-upload"></i>
                </a>
              </figure>
              <div class="portfolio-info">
                <label class="custom-control overflow-checkbox">
                  <!-- checkbox value is set as album id -->
                  <input type="checkbox" class="overflow-control-input cbalbum" 
                         name="album[]" value="<?php echo $value['id']; ?>">
                  <span class="overflow-control-indicator"></span>
                </label>
                <div style="margin-top: -32px;margin-left: 35px">
                  <h4><a onclick="window.open('fbalbum_slider.php?id=<?php echo $value['id']; ?>','_blank','toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=700,height=500')" style="cursor: pointer;"><?php echo $value["name"]; ?></a></h4>
                  <!-- display the total photoes of album -->
                  <p><?php echo $value['count']; ?> Photos</p>
                </div>
              </div>
            </div>
          </div>
        <?php } ?> <!-- end foreach -->
        </div>
    </div>

      </div>
  </form>
    </section><!-- #portfolio -->
  </main>

  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="container">
      <div class="credits">
        <!-- developer resume link -->
        Dveloped with &hearts; by <a href="https://patelmargi.azurewebsites.net/resume">Margi patel</a>
      </div>
    </div>
  </footer><!-- #footer -->

  <!-- Back-to-top button -->
  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!--==========================
    Popup Model
  ============================-->
  <div id="myModal" class="modal">
    <div class="modal-content">
      <h2>Album Not Selected!</h2>
      <P>Please,select the album first and then go to the selected album</P>
      <p><button class="tryagain">Try Again!</button></p> 
    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/isotope/isotope.pkgd.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/touchSwipe/jquery.touchSwipe.min.js"></script>

  <!-- Main Javascript File -->
  <script src="js/main.js"></script>

  <script type="text/javascript">

    var checkboxes = document.getElementsByClassName("cbalbum"); // Get the checkbox values
    var modal = document.getElementById('myModal'); // Get the modal id
    var button = document.getElementsByClassName("tryagain")[0]; // Get the element that closes the modal

    // Get the all album ids and submit the form to download the album
    $('#select_download_all').click(function() {
      var form = document.getElementById("form_album"); //get the element by id
      form.action = "downloadalbum.php"; //set the action property of form
      //set all checkbox check property true
      for(i=0;i<checkboxes.length;i++)
      {
        checkboxes[i].checked = true;
      }
      form.submit(); //submit form
    });

    // Get the all album ids and submit the form to upload the album
    $('#select_upload_all').click(function(){
      var form = document.getElementById("form_album") //get the element by id
      form.action = "uploadalbum.php"; //set the action property of form
      //set all checkbox check property true
      for(i=0;i<checkboxes.length;i++)
      {
        checkboxes[i].checked = true;
      }
      form.submit(); //submit form
    });

    // Get the selected album ids and submit the form to download the album
    $('#select_download').click(function(){
      var form = document.getElementById("form_album") //get the element by id
      form.action = "downloadalbum.php"; //set the action property of form
      var count=0;
      //count the checkbox having property true
      for(i=0;i<checkboxes.length;i++)
      {
        if(checkboxes[i].checked==true)
          count++;
      }
      if(count==0)
        modal.style.display = "block"; //if any checkbox is not selected then display the modal popup
      else
        form.submit(); //submit the form
    });

    // Get the selected album ids and submit the form to upload the album
    $('#select_upload').click(function(){
      var form = document.getElementById("form_album") //get the element by id
      form.action = "uploadalbum.php"; //set the action property of form
      var count=0;
      //count the checkbox having property true
      for(i=0;i<checkboxes.length;i++)
      {
        if(checkboxes[i].checked==true)
          count++;
      }
      if(count==0)
        modal.style.display = "block"; //if any checkbox is not selected then display the modal popup
      else
        form.submit(); //submit the form
    });

    // When the user clicks on button, close the modal
    button.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function(event) {
    //     if (event.target == modal) {
    //         modal.style.display = "none";
    //     }
    // }
  </script>
</body>
</html>