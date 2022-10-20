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
        <h1 class="display-3 text-uppercase text-white mb-3">My Publications</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a href="index.php">Home</a></h6>
            <h6 class="text-white m-0 px-3">/</h6>
            <h6 class="text-uppercase text-white m-0">My Publications</h6>
        </div>
    </div>
    <br>
<!-- Page Header End -->

    <!-- Publications Start -->
    <div id="publ" class="container-fluid">
        <h1 class="heading-style text-white">New Publications</h1>
        <?php
        // select all the papers from the user
        $sql = "SELECT * FROM papers WHERE status=0";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<ul class = "list-group">
            <li class="list-group-item"> <strong>Papers:</strong></li>';
            // go through each row found
            while ($row = $result->fetch_assoc()) {
                $paper_title = $row["title"];
                // display paper with a link to download it 
                echo '<li class="list-group-item paper-style-pending"> <a target="_blank" class="text-dark" href=papers/' . $row["filename"] . '>' . $row["title"] . ' - ' . $row["author"] . ' (' . $row["year"] . ') <i class="bi bi-download"></i></a></li>';
                // get all recommended changes made to this paper and display them with the email of the person who recommended the changes
                $query = "SELECT * FROM recommended_changes WHERE paper='$paper_title'";
                $rec_ch_result = $conn->query($query);
                if ($rec_ch_result->num_rows > 0) {
                    echo '<ul class = "list-group">
                    <li class="list-group-item"> <strong>Recommended changes:</strong></li>';
                    while ($rec_ch_row = $rec_ch_result->fetch_assoc()) {
                        echo '<li class="list-group-item"> <p class="link"> by '. $rec_ch_row['email'] . '</p><textarea class="comment-field" readonly>' . $rec_ch_row['remark'] . '</textarea> </li> </ul>'; 
                    }
                }
                // accept paper form with button
                echo '<div class="btn-toolbar" role="group" aria-label="Basic example">';
                echo '<p> <form method="post" action="publications_cc.php?title=' . $paper_title . '"><br>';
                include('php/errors.php');
                echo '<button type="submit" class="btn" name="accept_paper">Accept</button>
                    </form>';
                // reject paper form with button
                echo '<form method="post" action="publications_cc.php?title=' . $paper_title . '"><br>';
                include('php/errors.php');
                echo '<button type="submit" class="btn" name="reject_paper">Reject</button>
                    </form></p></div><br>';
                // button to get to recommend changes paper page
                $rec_changes_page = './recommend_changes.php?title='.$paper_title;
                echo '<a class="btn" href='.$rec_changes_page.'>Recommend changes<a><br><br>';
            }
            echo '</ul>';
        }
        ?>
        <?php
        if (isset($_GET['title'])) {
            $title = $_GET['title'];

            echo '<form method="post" action="review.php?title=' . $title . '">';
            include('php/errors.php');
            echo '<textarea name="comment" id="comment" placeholder="Write your comment here..." class="comment-field"></textarea>';

            echo '<button type="submit" class="btn" name="review_paper">Post review</button>
    </form>';
        }
        ?>
    </div> <br>
    <!-- Publications End -->

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