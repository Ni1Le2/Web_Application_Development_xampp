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
        <h1 class="display-3 text-uppercase text-white mb-3">Papers & Posters</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a href="index.php">Home</a></h6>
            <h6 class="text-white m-0 px-3">/</h6>
            <h6 class="text-uppercase text-white m-0">Papers & Posters</h6>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Overview Start -->
    <div id="overview" class="container-fluid">
        <h1 class="heading-style text-white">Overview</h1>
        <div class="text-section"> This is the textbot for the overview section. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer feugiat diam ex, eu consequat eros dignissim ac. Morbi ac cursus leo. Aliquam erat volutpat. Donec a eleifend ligula. Etiam mollis tempus facilisis. Nullam eu diam dapibus, posuere sem dapibus, elementum orci. Quisque at arcu egestas, ornare nulla a, pharetra ipsum. Vivamus viverra sollicitudin risus et viverra. Donec eget euismod sem, non mollis ipsum. Proin massa leo, blandit posuere elit vitae, tristique tincidunt ex. Nunc semper bibendum enim sit amet ultrices. Quisque tellus tortor, pharetra ut auctor id, tempus quis nulla. Suspendisse nisi odio, dictum id scelerisque suscipit, sollicitudin nec nulla. In maximus nisi sed nulla malesuada, quis tincidunt velit sodales. Sed sodales nibh turpis, malesuada hendrerit eros lobortis ut.</div>
        <br>
    </div>
    <!-- Overview End -->

    <!-- Topics Start -->
    <div id="topics" class="container-fluid">
        <h1 class="heading-style text-white">Topics</h1>
        <ul class="list-group">
            <li class="list-group-item"><strong>The topics of the conference are:</strong></li>
            <li class="list-group-item">Topic 1</li>
            <li class="list-group-item">Topic 2</li>
            <li class="list-group-item">Topic 3</li>
    </div> <br>
    <!-- Topics End -->

    <!-- Publications Start -->
    <div id="publ" class="container-fluid">
        <h1 class="heading-style text-white">Publications</h1>
        <h2>Confirmed Publications</h2>


        <?php
        // php code to retrieve all the papers + info from the database
        $servername = "localhost";
        $username = "nico";
        $password = "yeah";
        $dbname = "websitedb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM papers WHERE status = 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<ul class = "list-group">
            <li class="list-group-item"> <strong>Papers:</strong></li>';

            // output data of each row
            while ($row = $result->fetch_assoc()) {
                // display paper part
                echo '<li class="list-group-item"> <a target="_blank" class="text-dark" href=papers/' . $row["filename"] . '>' . $row["title"] . ' - ' . $row["author"] . ' (' . $row["year"] . ') <i class="bi bi-download"></i></a></li>';
                $paper_title = $row['title'];
                $query = mysqli_query($conn, "SELECT AVG(rating) as AVGRATE FROM reviews WHERE paper_title='$paper_title'");
                $row = mysqli_fetch_array($query);
                $AVGRATE = round($row['AVGRATE'], 1);
                $query = mysqli_query($conn, "SELECT count(rating) as NumberRates from reviews WHERE paper_title='$paper_title'");
                $row = mysqli_fetch_array($query);
                $NumberRates = $row['NumberRates'];
                echo '<li class="list-group-item"> <p> Average rating: ' . $AVGRATE . ' (Total ratings: ' . $NumberRates . ')</p></li>';
            }

            echo '</ul> <br>';
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>

        <h2>Upload Publication</h2>
        <a href=upload.php class="text-dark"> <i class="bi bi-arrow-right"></i> Click here to upload a publication <i class="bi bi-upload"></i></a>
    </div> <br>
    <!-- Publications End -->

    <!-- Author Guidelines Start -->
    <div id="author_guidelines" class="container-fluid">
        <h1 class="heading-style text-white">Author Guidelines</h1>
    </div> <br>
    <!-- Author Guidelines End -->

    <!-- Deadlines Start -->
    <div id="deadlines" class="container-fluid">
        <h1 class="heading-style text-white">Deadlines</h1>

        <ul class="list-group">
            <li class="list-group-item">deadline registration: 01.11.2022</li>
            <li class="list-group-item">deadline paper submission: 15.11.2022</li>
            <li class="list-group-item">final decision on papers and speakers: 29.11.2022</li>
            <li class="list-group-item">sign up window to attend conference: 15.11.2022 - 29.11.2022</li>
    </div> <br>
    <!-- Deadlines End -->

    <!-- Footer Start -->
    <div class="footer container-fluid position-relative bg-dark bg-light-radial text-white-50 py-6 px-5">
        <div class="row g-5">
            <div class="col-lg-6 pe-lg-5">
                <a href="index.php" class="navbar-brand">
                    <h1 class="m-0 display-4 text-uppercase text-white"><i class="bi bi-building text-primary me-2"></i>WEBUILD</h1>
                </a>
                <p>Aliquyam sed elitr elitr erat sed diam ipsum eirmod eos lorem nonumy. Tempor sea ipsum diam sed clita dolore eos dolores magna erat dolore sed stet justo et dolor.</p>
                <p><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</p>
                <p><i class="fa fa-phone-alt me-2"></i>+012 345 67890</p>
                <p><i class="fa fa-envelope me-2"></i>info@example.com</p>
                <div class="d-flex justify-content-start mt-4">
                    <a class="btn btn-lg btn-primary btn-lg-square rounded-0 me-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-lg btn-primary btn-lg-square rounded-0 me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-lg btn-primary btn-lg-square rounded-0 me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-lg btn-primary btn-lg-square rounded-0" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="row g-5">
                    <div class="col-sm-6">
                        <h4 class="text-white text-uppercase mb-4">Quick Links</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Home</a>
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>About Us</a>
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Our Services</a>
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Meet The Team</a>
                            <a class="text-white-50" href="#"><i class="fa fa-angle-right me-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="text-white text-uppercase mb-4">Popular Links</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Home</a>
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>About Us</a>
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Our Services</a>
                            <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Meet The Team</a>
                            <a class="text-white-50" href="#"><i class="fa fa-angle-right me-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <h4 class="text-white text-uppercase mb-4">Newsletter</h4>
                        <div class="w-100">
                            <div class="input-group">
                                <input type="text" class="form-control border-light" style="padding: 20px 30px;" placeholder="Your Email Address"><button class="btn btn-primary px-4">Sign Up</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark bg-light-radial text-white border-top border-primary px-0">
        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="py-4 px-5 text-center text-md-start">
                <p class="mb-0">&copy; <a class="text-primary" href="#">Your Site Name</a>. All Rights Reserved.</p>
            </div>
            <div class="py-4 px-5 bg-primary footer-shape position-relative text-center text-md-end">
                <p class="mb-0">Designed by <a class="text-dark" href="https://htmlcodex.com">HTML Codex</a></p>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>