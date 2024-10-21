<?php
$servername="localhost";
$username="root";
$password="Sanukavu@1424";
$dbname="elearning_db";
$conn=new mysqli($servername , $username , $password , $dbname);
if($conn->connect_error){
    die("connection failed:".$conn->connect_error);
}

?>