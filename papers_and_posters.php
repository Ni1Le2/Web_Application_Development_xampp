<?php
session_start();

// if user signed in, check if it's a researcher or a conference chair user
if (isset($_SESSION['conference_chair'])) {
    // if user is researcher go to researcher-adjusted page
    if ($_SESSION["conference_chair"] == 0) {
        header("Location: ./papers_and_posters_researcher.php");
        exit();
    // if user is conference chair go to cc-adjusted page
    } elseif ($_SESSION["conference_chair"] == 1) {
        header("Location: ./papers_and_posters_cc.php");
        exit();
    }}
else {
        header("Location: ./papers_and_posters_loggedout.php");
        exit();
    }
