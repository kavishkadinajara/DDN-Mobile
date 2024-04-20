<?php
include("./connection_db/dbconnect.php");
session_start();
session_regenerate_id(true);

if(mysqli_connect_errno()){
    echo "Connection Failed".mysqli_connect_error();
    exit;
}
?>