<?php

$host = "sql307.infinityfree.com";
$user = "if0_42879226";
$pass = "zwq6Z5NZCpk7nn";
$dbname = "if0_42879226_bakery";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>