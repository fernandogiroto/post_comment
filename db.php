<?php
    $conn = mysqli_connect("localhost","root","","comm2");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
