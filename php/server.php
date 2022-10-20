<?php
// start session if it has not been started (important for Session-variables)
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

// initializing variable for errors, all errors that might occur will be pushed to this array
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
    // push corresponding error into $errors array to display it
    // remarks: no need to check password length, as it is md5 encrypted and will thus always be of length 32
    //          no need to check title as it can only be chosen (not typed) and will never be too long
    // check for string lenghts to ensure no string is larger than the space reserved in the db-table
    if (strlen($firstName) > 15) {
        array_push($errors, "The entered first name is too long, it can only have a maximum of 15 characters");
    }
    if (strlen($surname) > 15) {
        array_push($errors, "The entered surname is too long, it can only have a maximum of 15 characters");
    }
    if (strlen($email) > 30) {
        array_push($errors, "The entered email is too long, it can only have a maximum of 30 characters");
    }
    if (strlen($affiliateOrganisation) > 30) {
        array_push($errors, "The entered organisation name is too long, it can only have a maximum of 30 characters");
    }
    // check if passwords match
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    // make sure email address is not used for another user
    $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $user_row = get_selected_lines($con, $user_check_query);
    if ($user_row) {
        if ($user_row['email'] === $email) {
            array_push($errors, "This email is already registered");
        }
    }
    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        // encrypt password to md5
        $password = md5($password_1);
        // newly registered users are always researcher, all conference chair users are pre-created
        $query = "INSERT INTO users(title,firstName, surname ,email, password, affiliateOrganisation, conference_chair)  VALUES ('$title',
            '$firstName', '$surname', '$email','$password', '$affiliateOrganisation', 0)";
        mysqli_query($con, $query);
        // log user into system by setting Session variables
        $_SESSION['email'] = $email;
        // newly registered users are always researchers, never conference chair
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
if (isset($_GET['logout'])) {
    // sign user out by unsetting all session variables and ending the session
    session_destroy();
    unset($_SESSION['email']);
    unset($_SESSION['conference_chair']);
    unset($_SESSION['success']);
    header('location: ../sign_in.php');
}

// Upload Paper
if (isset($_POST['upload'])) {
    // get title and year from the user input
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $year =  $_REQUEST['year'];
    // check for errors in input
    // title with spaces lead to errors, so this is the easiest fix
    if (strpos($title, ' ') !== false) {
        array_push($errors, 'Please do not use spaces in your paper title, use "_" instead');
      }
    // length of title max 100 in db-table
    if (strlen($title) > 100) {
        array_push($errors, "The entered title is too long, it can only have a maximum of 100 characters");
    }
    // year must be 4 digit, no papers before 1980 accepted
    if ($year < 1980 or $year > 2022) {
        array_push($errors, "Year must be a 4 digit number between 1980 and 2022");
    }
    // only logged in users can upload papers, so this variable is always set, still to double check
    if (isset($_SESSION['email'])) {
        $email =  $_SESSION['email'];
    } else {
        array_push($errors, "Please register/sign in first");
    }

    // get file name from form and check if the name is not in the folder papers yet
    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    // make sure that no file with the same name has been uploaded to folder or db-table
    $filename_check_query = "SELECT * FROM papers WHERE filename='$file_name'";
    $file_name_rows = get_selected_lines($con, $filename_check_query);
    if (file_exists("./papers/" . $file_name) or $file_name_rows) {
        array_push($errors, "Please save the paper under a different filename");
    }

    // make sure that no paper with the same title has been uploaded
    $paper_check_query = "SELECT * FROM papers WHERE title='$title'";
    $paper_rows = get_selected_lines($con, $paper_check_query);
    if ($paper_rows) {
        array_push($errors, "Please choose a different name for the paper");
    }

    // if there are no errors add paper to the database
    if (count($errors) == 0) {
        // add file (under the same name) to the folder "papers"
        move_uploaded_file($file_tmp, "./papers/" . $file_name);
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
    // only logged in users can review paper
    if (isset($_SESSION['email'])) {
        $email =  $_SESSION['email'];
    } else {
        array_push($errors, "Please register/sign in first");
    }
    // set rating and comment variables according to the input in the form tag
    // no need to check rating, as it can only be chosen, not entered
    $rating = mysqli_real_escape_string($con, $_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    // length of comment max 200 in db-table
    if (strlen($comment) > 200) {
        array_push($errors, "The entered comment is too long, it can only have a maximum of 200 characters");
    }

    // get the paper title passed via link in the form
    if (isset($_GET['title'])) {
        $paper_title = mysqli_real_escape_string($con, $_GET['title']);
    }
    // make sure that this user has not yet commented/reviewed this paper (only one review per person per paper)
    $user_check_query = "SELECT * FROM reviews WHERE email='$email' AND paper_title='$paper_title' LIMIT 1";
    $user_rows = get_selected_lines($con, $user_check_query);
    if ($user_rows) {
        array_push($errors, "You have already reviewed this paper");
    }

    // add review and comment to the database, if there are no errors in the form
    if (count($errors) == 0) {
        $query = "INSERT INTO reviews(email, rating, comment, paper_title)  VALUES ('$email',
            '$rating', '$comment', '$paper_title')";
        mysqli_query($con, $query);
    }
    header("Location: ./publications.php#publ");
}


// ********** Conference Chair operations ********** 

// Add news to homepage
if (isset($_POST['add_news'])) {
    // check if user is logged in and conference chair (if)
    if (isset($_SESSION['conference_chair']) and $_SESSION['conference_chair'] == 1) {
        $email =  $_SESSION['email'];
    } else {
        array_push($errors, "Please sign in as conference chair to access this");
    }
    $news_message = mysqli_real_escape_string($con, $_POST['news']);
    // length of news msg max 100 in db-table
    if (strlen($news_message) > 100) {
        array_push($errors, "The entered comment is too long, it can only have a maximum of 100 characters");
    }
    if (count($errors) == 0) {
        $query = "INSERT INTO news(email, message) VALUES ('$email',
    '$news_message')";
        mysqli_query($con, $query);
    }
    header("Location: ./index.php");
}

// Remove news
if (isset($_POST['remove_news'])) {
    if (isset($_GET['msg_id'])) {
        $news_id = (int) $_GET["msg_id"];
        $remove_query = "DELETE FROM news WHERE ID='$news_id'";
        mysqli_query($con, $remove_query);
    }
    header("Location: ./index.php");
}

// Reommend changes to paper
if (isset($_POST['rec_changes'])) {
    // check if user is logged in and conference chair (if)
    if (isset($_SESSION['conference_chair']) and $_SESSION['conference_chair'] == 1) {
        $email =  $_SESSION['email'];
    } else {
        array_push($errors, "Please sign in as conference chair to access this");
    }
    // get remark from form
    $remark = mysqli_real_escape_string($con, $_POST['remark']);
    // length of news msg max 200 in db-table
    if (strlen($remark) > 200) {
        array_push($errors, "The entered remark is too long, it can only have a maximum of 200 characters");
    }

    // get the paper title passed via link in the form
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
        $remove_query = "UPDATE papers SET status=2 WHERE title='$paper_title'";
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
