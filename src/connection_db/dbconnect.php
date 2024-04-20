<?php
    $con = mysqli_connect('#hostName', '#userName', '#pasword@', '#databaseName');

    // Check connection
    if (!$con) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }
?>