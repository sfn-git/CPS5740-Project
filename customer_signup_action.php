<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Action</title>
    <?php include 'libraries.php'?>
</head>
<body>
<?php

    include 'dbconfig.php';
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $user['username'] = mysqli_real_escape_string($conn, $_POST['username']);
    $user['password'] = mysqli_real_escape_string($conn, $_POST['password']);
    $user['passwordConfirm'] = mysqli_real_escape_string($conn, $_POST['passwordConfirm']);
    $user['firstName'] = mysqli_real_escape_string($conn, $_POST['firstName']);
    $user['lastName'] = mysqli_real_escape_string($conn, $_POST['lastName']);
    $user['telephone'] = mysqli_real_escape_string($conn, $_POST['telephone']);
    $user['address'] = mysqli_real_escape_string($conn, $_POST['address']);
    $user['city'] = mysqli_real_escape_string($conn, $_POST['city']);
    $user['zipcode'] = mysqli_real_escape_string($conn, $_POST['zipcode']);
    $user['state'] = mysqli_real_escape_string($conn, $_POST['state']);

    // Checks
    foreach($user as $u => $u_value){
        if($u_value == ""){
            mysqli_close($conn);
            die("<div class='error-message'>$u is blank. Please <a href='customer_signup.php'>try again</a></div>");
        }
    }

    $sql = "SELECT * FROM 2020F_nadeems.CUSTOMER WHERE login_id='" . $user['username'] . "'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        mysqli_close($conn);
        die("<div class='error-message'>User " . $user['username'] ." already exist. Please <a href='customer_signup.php'>try again.</a></div>");
    }

    if(!( $user['password'] == $user['passwordConfirm'])){
        mysqli_close($conn);
        die("<div class='error-message'>Passwords do not match. Please <a href='customer_signup.php'>try again</a></div>");
    }

    //After Checks
    $sql = "INSERT INTO 2020F_nadeems.CUSTOMER (login_id, password, first_name, last_name, TEL, address, city, zipcode, state) VALUES ( '" . $user['username'] . "','" . $user['password'] . "','" . $user['firstName'] . "','" . $user['lastName'] . "','" . $user['telephone'] . "','" . $user['address'] . "','" . $user['city'] . "','" . $user['zipcode'] . "','" . $user['state'] . "')";

    if(mysqli_query($conn, $sql)){
        echo "<div class='success-message'>Successfully Signed up!</div>";
    }else{
        echo "<div class='error-message'>Something went wrong :( </div>";
    }

    mysqli_close($conn);

?>
</body>
</html>