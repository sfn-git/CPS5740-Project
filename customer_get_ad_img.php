<?php 

    $id=$_GET['id'];

    include('dbconfig.php');
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $sql = "SELECT image FROM CPS5740.Advertisement WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);

    header("Content-type: image/jpeg");
    echo $row["image"];

?>