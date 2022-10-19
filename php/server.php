<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// connect to mysql database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'websitedb';

$con = mysqli_connect($host, $user, $password, $database);

// Check connection
if ($con === false) {
    die("ERROR: Could not connect. "
        . mysqli_connect_error());
}

// initializing variable for errors
$errors = array();

// function to simplify getting results of select statements
function get_selected_lines($con, $query)
{
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_assoc($result);
    return $rows;
}


// ********** Researcher operations ********** 

// User Registration:
if (isset($_POST['reg_user'])) {
    // get input values from registration form
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $surname = mysqli_real_escape_string($con, $_POST['surname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password_1 = mysqli_real_escape_string($con, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($con, $_POST['password_2']);
    $affiliateOrganisation = mysqli_real_escape_string($con, $_POST['affiliateOrganisation']);

    // validation: ensure that the form is correctly filled
    // by adding (array_push()) corresponding error unto $errors array
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    // now we need to check the database to make sure the email address is not used for another user
    $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $user_row = get_selected_lines($con, $user_check_query);

    if ($user_row) {
        if ($user_row['email'] === $email) {
            array_push($errors, "email already registered");
        }
    }
    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        // encrypt password
        $password = md5($password_1);
        $query = "INSERT INTO users(title,firstName, surname ,email, password, affiliateOrganisation, conference_chair)  VALUES ('$title',
            '$firstName', '$surname', '$email','$password', '$affiliateOrganisation', 0)";
        mysqli_query($con, $query);
        $_SESSION['email'] = $email;
        // newly registered users are always researcher, all conference chair users are pre-created
        $_SESSION['conference_chair'] = 0;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}

// User Sign In
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $password = mysqli_real_escape_string($con, $_POST['password']);
    if (count($errors) == 0) {
        // check for encrypted password
        $password = md5($password);
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $user_rows = get_selected_lines($con, $query);
        // if a user with this email and password is found, he is logged in, otherwise an error is displayed
        if ($user_rows) {
            $_SESSION['email'] = $email;
            $_SESSION['conference_chair'] = $user_rows["conference_chair"];
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

// User Sign Out

// Upload Paper
if (isset($_POST['upload'])) {
    $title = $_REQUEST['title'];
    $year =  $_REQUEST['year'];
    // only logged in users can upload papers, so this variable is always set, still to double check
    if (isset($_SESSION['email'])) {
        $email =  $_SESSION['email'];
    } else {
        array_push($errors, "Please register/sign in first.");
    }

    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    move_uploaded_file($file_tmp, "./papers/" . $file_name);

    // make sure that no paper with the same name has been uploaded
    $paper_check_query = "SELECT * FROM papers WHERE title='$title'";
    $paper_rows = get_selected_lines($con, $paper_check_query);

    if ($paper_rows) {
        array_push($errors, "Please choose a different name for the paper.");
    }

    // make sure that no file with the same name has been uploaded
    $filename_check_query = "SELECT * FROM papers WHERE filename='$file_name'";
    $file_name_rows = get_selected_lines($con, $filename_check_query);

    if ($file_name_rows) {
        array_push($errors, "Please choose a different name for the file.");
    }

    // if there are no errors add paper to the database
    if (count($errors) == 0) {
        // get the full name of the user
        $user_query = "SELECT firstName, surname FROM users WHERE email = '$email'";
        $row = get_selected_lines($con, $user_query);
        $firstName = $row['firstName'];
        $surname = $row['surname'];
        // set Author's name to the users name
        $author = $firstName . " " . $surname;
        $status = 0; // status 0 = not decided, 1 = accepted, 2 = rejected
        $insertquery =
            "INSERT INTO papers(email, author, title, year, filename, status) VALUES('$email', '$author', '$title', '$year', '$file_name', '$status')";
        $iquery = mysqli_query($con, $insertquery);
        header("Location: ./upload_success.php");
    }
}


// Review Paper
if (isset($_POST['review_paper'])) {
    // get current user
    $email = $_SESSION['email'];
    // set rating and comment variables according to his input in the form tag
    $rating = mysqli_real_escape_string($con, $_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    // get the paper title passed in the form
    if (isset($_GET['title'])) {
        $paper_title = $_GET['title'];
    }

    // make sure that this user has not yet commented/reviewed this paper (only one review per person per paper)
    $user_check_query = "SELECT * FROM reviews WHERE email='$email' AND paper_title='$paper_title' LIMIT 1";
    $user_rows = get_selected_lines($con, $user_check_query);

    if ($user_rows) {
        array_push($errors, "You have already reviewed this paper.");
    }
    // add review and comment to the database, if there are no errors in the form
    if (count($errors) == 0) {
        $query = "INSERT INTO reviews(email, rating, comment, paper_title)  VALUES ('$email',
            '$rating', '$comment', '$paper_title')";
        mysqli_query($con, $query);
    }
    header("Location: ./papers_and_posters.php#publ");
}

// ********** Conference Chair operations ********** 

// Add news to homepage
if (isset($_POST['add_news'])) {
    $email = $_SESSION['email'];
    $news_message = mysqli_real_escape_string($con, $_POST['news']);
    $query = "INSERT INTO news(email, message) VALUES ('$email',
    '$news_message')";  
    mysqli_query($con, $query);
    header("Location: ../index.php");

}

// Remove news
if (isset($_POST['remove_news'])) {
    if (isset($_GET['msg_id'])) {
        $news_id = (int) $_GET["msg_id"];  
        $remove_query = "DELETE FROM news WHERE ID='$news_id'";
        mysqli_query($con, $remove_query);
        
    } else {
        $news_message = $msg_id;    }
    //header("Location: ../index.php");
}

// Reommend Changes to paper
if (isset($_POST['rec_changes'])) {
    // get current user, user can only activate this POST if he is conference chair user
    $email = $_SESSION['email'];
    $cc_check_query = "SELECT conference_chair FROM users WHERE email='$email'";
    $cc = get_selected_lines($con, $cc_check_query)['conference_chair'];
    if ($cc == 0) {
        array_push($errors, "Only conference chair users can recommend changes to a paper");
    }
    // get remark from form
    $remark = mysqli_real_escape_string($con, $_POST['remark']);
    // get the paper title passed in the form
    if (isset($_GET['title'])) {
        $paper_title = $_GET['title'];
    }
    if (count($errors) == 0) {
        $query = "INSERT INTO recommended_changes(email, paper, remark)  VALUES ('$email',
    '$paper_title', '$remark')";
        mysqli_query($con, $query);
    }
    header("Location: ./new_publications.php");

}

// Reject paper
if (isset($_POST['reject_paper'])) {
    // get the paper title passed in the form
    if (isset($_GET['title'])) {
        $paper_title = $_GET['title'];
        // set status to 2 to indicate paper has been rejected
        $remove_query = "UPDATE papers SET status=2 WHERE title='$title'";
        mysqli_query($con, $remove_query);
    }
}

// Accept paper
if (isset($_POST['accept_paper'])) {
    // get the paper title passed in the form
    if (isset($_GET['title'])) {
        $title = $_GET['title'];
        // set status to 2 to indicate paper has been accepted
        $remove_query = "UPDATE papers SET status=1 WHERE title='$title'";
        mysqli_query($con, $remove_query);
    }
}
