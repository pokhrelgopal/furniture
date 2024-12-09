<?php
function checkLogin()
{
    session_start(); // Ensure the session is started

    // Check if user session variables are set
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        // Redirect to login page if not logged in
        header("Location: /furniture/login.php");
        exit; // Stop further execution
    }
}

function checkAdmin()
{
    session_start(); // Ensure the session is started

    // Check if user session variables are set
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'ADMIN') {
        // Redirect to login page if not logged in
        header("Location: /furniture/login.php");
        exit; // Stop further execution
    }
}
