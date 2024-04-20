<?php
$loginError = ''; // Initialize $loginError

session_start();

// Check for a logout action
if (isset($_GET['logout'])) {
    // Unset and destroy the session variables
    session_unset();
    session_destroy();

    // Redirect to the login page or any other page you prefer
    header('Location: login.php'); // Replace 'login.php' with your login page
    exit();
}

?>
