<?php include('php/server.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ICATH'2022 Website - Web Application Development</title>
    <meta name="author" content="Nico , Onni Kivistoe">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

    <!-- CSS Stylesheet -->
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
    <div id="navbar" class="container-fluid sticky-top bg-dark bg-light-radial shadow-sm px-5 pe-lg-0" id="navbar">
    </div>
    <script src="js/nav_loggedout.js"></script>
    <br>
    <!-- Navbar End -->

    <!-- Sign in Window Start -->
    <h1 class="heading-style text-white">Sign in:</h1>
    <form method="post" action="sign_in.php">
      <?php include('php/errors.php'); ?>

        <label for="email"><b>Email:</b></label><br>
        <input type="text" placeholder="Enter your email..." name="email" id="email" required><br>
        <label for="password"><b>Password:</b></label><br>
        <input type="password" placeholder="Enter your password..." id="password" name="password" required>
        <br>
        <a href="registration.php" class="container link">Not registered yet? - Register here! </a> <br>
        <button type="submit" class="btn" name="login_user">Sign in</button> <br>
    </form>

    <!-- Sign in Window End -->

        <!-- Footer Start -->
        <div class="footer container-fluid position-relative bg-dark bg-light-radial text-white-50 py-5 px-5">
        <div class="row g-5">
            <div class="col-lg-6 pe-lg-5">
                <a href="index.php" class="navbar-brand">
                    <h1 class="m-0 display-7 text-uppercase text-white"><img src="img/ICATH_logo.jpg" alt="ITAC_image" width="50" height="50" />ICATH2022</h1>
                </a>
                <p> Nico Leng:</p>
                <p><i class="fa fa-map-marker-alt me-2"></i>13 Jackson Kaujeua Street (Windhoek, Namibia)</p>
                <p><i class="fa fa-envelope me-2"></i>niconico.leng@googlemail.com</p>

            </div>
            <div class="col-lg-6 ps-lg-5">
                <p style="margin:5em;"></p>
                <p> Onni Kivistoe:</p>
                <p><i class="fa fa-map-marker-alt me-2"></i>13 Jackson Kaujeua Street (Windhoek, Namibia)</p>
                <p><i class="fa fa-envelope me-2"></i>onni.kivisto@gmail.com</p>

            </div>
        </div>
    </div>
    <!-- Footer End -->
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>