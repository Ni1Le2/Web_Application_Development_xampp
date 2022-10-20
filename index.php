<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
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
    <div class="container-fluid page-header">
        <h1 class="display-3 text-uppercase text-white mb-3">Home</h1>
    </div>
    <br>
    <!-- Page Header End -->

    <!-- Home Start -->
    <div id="news" class="container-fluid">
        <h1 class="heading-style text-white">News:</h1>
        <?php
        // connect to websitedb
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
        // to display all news
        $query = "SELECT * FROM news";
        $message_res = $conn->query($query);

        if ($message_res->num_rows > 0) {
            while ($row = $message_res->fetch_assoc()) {
                // display news message
                echo '<div class="small-text-section">' . $row['message'] . '</div>';
                // if user is conference chair he can add news
                if (isset($_SESSION['conference_chair']) and $_SESSION['conference_chair'] == 1) {
                    // form + button to remove this message
                    echo '<form method="post" action="index.php?msg_id=' . $row['ID'] . '"><br>';
                    echo '<button type="submit" class="btn" name="remove_news">Remove</button></form>';
                }
                echo '<br>';
            }
        } else {
            echo '<ul class = "list-group">
           <li class="list-group-item"> <strong>No news have been announced.</strong></li></ul><br>';;
        }
        ?>
        <?php
        // if there is a conference chair value assigned to session, a user is logged in
        if (isset($_SESSION['conference_chair'])) {
            // if user is conference chair he can add news
            if ($_SESSION['conference_chair'] == 1) {
                echo '<form method="post" action="index.php">';
                echo '<textarea name="news" id="news" placeholder="Write news announcement here..." class="comment-field"></textarea>';
                echo '<button type="submit" class="btn" name="add_news">Add News</button></form><br><br>';
            }
        }
        ?>

        <!-- About Start -->
        <h1 class="heading-style text-white">About:</h1> <br>
        <h2>Conference venue and time:</h2> <br>
        <div class="text-section">
            The 4th International Conference on Advanced Technologies for Humanity (ICATH'2022) is organized by the Moroccan School of Engineering Sciences (EMSI) in collaboration with nationals and internationals institutes (INSEA, INPT, UM5-FSR, ENSA-Kenitra, Karadeniz Technical University and Future Univeristy of Egypt). This edition will be held in Marrakech, the historical and touristic city of Morocco, from 11 to 12 November 2022, at Riad Ennakhil - Hotel & SPA.
        </div><br>
        <h2>Aim of ICATH'2022:</h2> <br>
        <div class="text-section">
            Advances in science and engineering have lower impact when the human perspective is ignored. For this reason, ICATH’2022 aims at establishing this vital link in order to magnify such benefits. This conference is focused on discussing practical and innovative technological solutions to everyday challenges that humans face.
        </div><br>
        <h2>Keynote speakers:</h2> <br>
        <div class="text-section">
            Keynote speakers for the conference include but are not limited to: Prof. Joong HEE LEE from Jeonbuk National University, Ahmet Can ALTUNIŞIK from Karadeniz Technical University, Amal El Fallah SEGHROUNI, and Rajan Sen from University of South Florida.
        </div><br><br>
        <!-- About End -->
    </div> <br>
    <!-- Home End -->

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