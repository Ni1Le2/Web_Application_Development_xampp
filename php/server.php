<?php
session_start();

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
    $result = mysqli_query($con, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['email'] === $email) {
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
        $results = mysqli_query($con, $query);
        $row = $results->fetch_assoc();
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['email'] = $email;
          $_SESSION['conference_chair'] = $row["conference_chair"];
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
    if(isset($_SESSION['email'])) {
        $email =  $_SESSION['email'];
    } else {
        array_push($errors, "Please register/sign in first.");
    }
    
    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    move_uploaded_file($file_tmp,"./papers/".$file_name);

    // make sure that no paper with the same name has been uploaded
    $paper_check_query = "SELECT * FROM papers WHERE title='$title'";
    $result = mysqli_query($con, $paper_check_query);
    $paper = mysqli_fetch_assoc($result);

    if ($paper) {
        array_push($errors, "Please choose a different name for the paper.");
    }

    // make sure that no file with the same name has been uploaded
    $filename_check_query = "SELECT * FROM papers WHERE filename='$file_name'";
    $result = mysqli_query($con, $filename_check_query);
    $file_name_check = mysqli_fetch_assoc($result);
    
    if ($file_name_check) {
        array_push($errors, "Please choose a different name for the file.");
    }

    // if there are no errors add paper to the database
    if (count($errors) == 0) {
        $user_query = "SELECT firstName, surname FROM users WHERE email = '$email'";
        $user_result = mysqli_query($con, $user_query);
        $row = mysqli_fetch_assoc($user_result);
        $firstName = $row['firstName'];
        $surname = $row['surname'];
        $author = $firstName." ".$surname;
        $status = 0; // status 0 = not decided, 1 = accepted, 2 = rejected
        $insertquery =
        "INSERT INTO papers(email, author, title, year, filename, status) VALUES('$email', '$author', '$title', '$year', '$file_name', '$status')";
        $iquery = mysqli_query($con, $insertquery);
        header("Location: ./upload_success.php");
    }
}


// Review Paper
if (isset($_POST['review_paper'])) {
    $email = $_SESSION['email'];
    $rating = mysqli_real_escape_string($con, $_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    $paper_title = $_GET['title'];
    echo $paper_title;

    // make sure that this user has not yet commented/reviewed this paper (only one review per person per paper)
    $user_check_query = "SELECT * FROM reviews WHERE email='$email' AND paper_title='$paper_title' LIMIT 1";
    $result = mysqli_query($con, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        array_push($errors, "You have already reviewed this paper.");
    }
    // add review and comment, if there are no errors in the form
    if (count($errors) == 0) {
        $query = "INSERT INTO reviews(email, rating, comment, paper_title)  VALUES ('$email',
            '$rating', '$comment', '$paper_title')";
        mysqli_query($con, $query);
    }
    header("Location: ./papers_and_posters.php#publ");
}

// Reject paper
if (isset($_POST['reject_paper'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'reject' && isset($_GET['title'])){
        $title = $_GET['title'];
        $remove_query = "UPDATE papers SET status=2 WHERE title='$title'";
        mysqli_query($con, $remove_query);
    }
}

// Accept paper
if (isset($_POST['accept_paper'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'accept' && isset($_GET['title'])){
        $title = $_GET['title'];
        $remove_query = "UPDATE papers SET status=1 WHERE title='$title'";
        mysqli_query($con, $remove_query);
    }
}