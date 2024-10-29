<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name ="crown";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if($db->connect_error){
    echo "gagal";
    die();
}

?>