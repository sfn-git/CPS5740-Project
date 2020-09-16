<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Action</title>
    <?php include 'libraries.php'?>
</head>
<body>
<?php
    if(!isset($_COOKIE["customer_id"])){
        die("<div class='error-message'>You must be logged to update your data. Login <a href='customer_login.php'>here</a></div>");
    }

    include('dbconfig.php');
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $id = $_COOKIE['customer_id'];
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $telephone = mysqli_real_escape_string($conn, $_POST['tel']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);

    $sql = "UPDATE 2020F_nadeems.CUSTOMER 
    SET password='$password', first_name='$firstName', last_name='$lastName', TEL='$telephone', address='$address', city='$city', zipcode='$zipcode', state='$state'
    WHERE customer_id='$id'";

    $result = mysqli_query($conn, $sql);

    if($result){
        echo "<div class='message'>Your information has been updated successfully! <a href='customer_home.php'>Click here</a> to go back home</div>";
    }else{
        echo "<div class='error-message'>Something went wrong! <a href='customer_home.php'>Click here</a> to go back home</div>";
    }

    mysqli_close($conn);

?>
</body>
</html>