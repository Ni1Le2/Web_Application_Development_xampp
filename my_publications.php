<?php include('php/server.php') ?>
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

// get current users email
$email = $_SESSION['email'];
?>

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
    <div class="container-fluid page-header ">
        <h1 class="display-3 text-uppercase text-white mb-3">My Papers</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a href="index.php">Home</a></h6>
            <h6 class="text-white m-0 px-3">/</h6>
            <h6 class="text-uppercase text-white m-0">My Papers</h6>
        </div>
    </div>
    <br>
    <!-- Page Header End -->

    <!-- Publications Start -->
    <div id="publ" class="container-fluid">
        <h1 class="heading-style text-white">My Publications</h1>
        <?php
        // select all the papers from the user
        $sql = "SELECT * FROM papers WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<ul class = "list-group">
            <li class="list-group-item"> <strong>Papers:</strong></li>';
            // go through each row found
            while ($row = $result->fetch_assoc()) {
                // display paper with a link to download it
                if ($row['status'] == 1) {
                    echo '<li class="list-group-item paper-style"> <a target="_blank" class="text-dark" href=papers/' . $row["filename"] . '>' . $row["title"] . ' - ' . $row["author"] . ' (' . $row["year"] . ') <i class="bi bi-download"></i>&nbsp&nbsp&nbsp (accepted)</a></li>';
                } elseif ($row['status'] == 0) {
                    echo '<li class="list-group-item paper-style-pending"> <a target="_blank" class="text-dark" href=papers/' . $row["filename"] . '>' . $row["title"] . ' - ' . $row["author"] . ' (' . $row["year"] . ') <i class="bi bi-download"></i>&nbsp&nbsp&nbsp (not accepted yet)</a></li>';
                } else {
                    echo '<li class="list-group-item paper-style-rejected"> <a target="_blank" class="text-dark" href=papers/' . $row["filename"] . '>' . $row["title"] . ' - ' . $row["author"] . ' (' . $row["year"] . ') <i class="bi bi-download"></i>&nbsp&nbsp&nbsp (rejected)</a></li>';
                }
                $paper_title = $row['title'];
                // select all recommended changes to this paper and dsiplay them
                $changes_query = "SELECT * FROM recommended_changes WHERE paper='$paper_title'";
                $changes_query_res = $conn->query($changes_query);
                // if there are any display all of them
                if ($changes_query_res->num_rows > 0) {
                    echo '<li class="list-group-item"> <strong>Recommended Changes:</strong></li>';
                    while ($message_row = $changes_query_res->fetch_assoc()) {
                        echo '<li class="list-group-item"> <textarea class="comment-field" readonly> ' . $message_row['remark'] . '</textarea> </li>';
                    }
                }
                // select all reviews to this paper
                $comment_query = "SELECT * FROM reviews WHERE paper_title='$paper_title'";
                $comment_query_res = $conn->query($comment_query);
                // if there are any display all of them
                if ($comment_query_res->num_rows > 0) {
                    echo '<li class="list-group-item"> <strong>Comments:</strong></li>';
                    while ($review_row = $comment_query_res->fetch_assoc()) {
                        echo '<li class="list-group-item"> <p>Rating: ' . $review_row['rating'] . '</p>';
                        echo '<textarea class="comment-field" readonly> ' . $review_row['comment'] . '</textarea> </li>';
                    }
                } else {
                    echo '<li class="list-group-item"> <p>(Your paper has not been rated yet. If it is rated, ratings and comments will be displayed here)</p>';
                }
            }
            echo '</ul> <br>';
        } else {
            echo '<p>You have not uploaded any papers yet.</p>'; 
        }
        ?>
        <h2>Upload Publication</h2>
        <a href=upload.php class="text-dark"> <i class="bi bi-arrow-right"></i> Click here to upload your publication <i class="bi bi-upload"></i></a><br>
        <br>
        <!-- Publications End -->

        <!-- My Comments Start -->
        <h1 class="heading-style text-white">My Comments</h1>

        <?php
        // select all the papers from other users
        $sql = "SELECT * FROM papers WHERE email!='$email' AND status = 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<ul class = "list-group">';
            // go through each row found
            while ($row = $result->fetch_assoc()) {
                $paper_title = $row['title'];
                // get the logged in researchers comments on other peoples papers
                $query = "SELECT * FROM reviews WHERE email='$email' AND paper_title='$paper_title'";
                $query_res = $conn->query($query);
                // if researcher already made a review, paper and the review are shown
                if ($query_res->num_rows > 0) {
                    echo '<li class="list-group-item paper-style"> <a target="_blank" class="text-dark" href=papers/' . $row["filename"] . '>' . $row["title"] . ' - ' . $row["author"] . ' (' . $row["year"] . ') <i class="bi bi-download"></i></a></li>';
                    $query_row = $query_res->fetch_assoc();
                    echo '<li class="list-group-item"> <p>Your rating: ' . $query_row['rating'] . '</p>';
                    echo '<textarea class="comment-field" readonly> ' . $query_row['comment'] . '</textarea> </li>';
                }
            }
            echo '</ul> <br>';
        } else {
            echo "You have not reviewed any papers yet.";
        }
        $conn->close();
        ?>
    </div> <br>
    <!-- My Comments End -->

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