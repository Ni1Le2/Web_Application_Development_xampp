<?php include('php/server.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>WEBUILD - Construction Company Website Template Free</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <meta content="Free HTML Templates" name="keywords">
   <meta content="Free HTML Templates" name="description">

   <!-- Favicon -->
   <link href="img/icon.ico" rel="icon">

   <!-- Google Web Fonts -->
   <link rel="preconnect" href="https://fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

   <!-- Icon Font Stylesheet -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

   <!-- Libraries Stylesheet -->
   <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
   <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
   <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

   <!-- Customized Bootstrap Stylesheet -->
   <link href="css/bootstrap.min.css" rel="stylesheet">

   <!-- Template Stylesheet -->
   <link href="css/style.css" rel="stylesheet">

</head>

<body>
   <!-- Banner Start -->
   <div class="container-fluid p-0">
      <div class="row">
         <img src="img/icath_banner.webp" alt="ITAC_banner" />
      </div>
   </div>
   <!-- Banner End -->

    <!-- Navbar Start -->
    <div id="navbar" class="container-fluid sticky-top bg-dark bg-light-radial shadow-sm px-5 pe-lg-0">
    </div>
    <?php 
    // if there is a conference chair value assigned to session, a user is logged in
    if (isset($_SESSION['conference_chair'])) {
        // check if user is researcher or conference chair and display respective navbar
        if ($_SESSION['conference_chair'] == 0) {
        echo '<script src="js/nav_loggedin_res.js"></script>';
        } else { // display loggedout navbar
        echo '<script src="js/nav_loggedin_cc.js"></script>';
        }
    } else {
        echo '<script src="js/nav_loggedout.js"></script>';
    }
    ?>
    <!-- Navbar End -->

   <!-- Page Header Start -->
   <div class="container-fluid page-header ">
      <h1 class="display-3 text-uppercase text-white mb-3">Registration</h1>
      <div class="d-inline-flex text-white">
         <h6 class="text-uppercase m-0"><a href="index.php">Home</a></h6>
         <h6 class="text-white m-0 px-3">/</h6>
         <h6 class="text-uppercase text-white m-0">Registration</h6>
      </div>
   </div>
   <!-- Page Header End -->

   <!-- Registration form start -->
   <h1 class="heading-style text-white">Registration:</h1>
   <form method="post" action="registration.php">
      <?php include('php/errors.php'); ?>
      <p>
         <label for="title">Title:</label>
         <input type="radio" id="title" name="title" checked="checked">
         <label for="title">None</label><br>
         <input type="radio" id="title" name="title">
         <label for="title">Ms.</label><br>
         <input type="radio" id="title" name="title">
         <label for="title">Mr.</label><br>
         <input type="radio" id="title" name="title">
         <label for="title">Dr.</label><br>
         <input type="radio" id="title" name="title">
         <label for="title">Prof.</label><br>
         <input type="radio" id="title" name="title">
         <label for="title">Doc..</label><br>
      </p>
      <p>
         <label for="firstName">First Name:</label>
         <input type="text" name="firstName" required>
      </p>
      <p>
         <label for="surname">Surname:</label>
         <input type="text" name="surname" required>
      </p>
      <p>
         <label for="email">Email:</label>
         <input type="email" name="email" required>
      </p>
      <p>
         <label>Password</label>
         <input type="password" name="password_1">
      </p>
      <p>
         <label>Confirm password</label>
         <input type="password" name="password_2">
      </p>
      <p>
         <label for="affiliateOrganisation">Affiliate Organisation:</label>
         <input type="text" name="affiliateOrganisation" required>
      </p>
      <button type="submit" class="btn" name="reg_user">Register</button>

      <p>
  		   <a href="sign_in.php">Already registered? - Sign in here</a>
  	   </p>
</form>
<!-- Registration form end -->

</body>

</html>