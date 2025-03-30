<!DOCTYPE html>
<html>

<?php include 'index/head.php'; ?>

<body>

  <div class="hero_area">

    <!-- header-->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="./index.php">
            <span>
            <img src="index/images/bpc.ico" alt="BPC Logo" class="logo-img">
                BULACAN POLYTECHNIC COLLEGE
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  ">
              <li class="nav-item active">
                <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index/team.php">Developers</a>
              </li>
              <li class="nav-item">
                <a class="nav-link"href="./login.php"> Login&nbsp; <i class="fa fa-user" aria-hidden="true"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    
  <?php include 'index/terms.php'; ?>
    <!-- main -->
    <?php include 'index/main.php'; ?>
  </div>
  <br>
  <!-- info -->
    <?php include 'index/info.php'; ?>

  <!-- footer  -->
  <?php include 'index/footer.php' ?>

</body>
   <!-- jQery -->
   <script type="text/javascript" src="index/js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="index/js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="index/js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->
</html>

