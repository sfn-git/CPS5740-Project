<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home</title>
    <?php include 'libraries.php'?>
</head>
<body>
<?php

    if(isset($_COOKIE["customer_id"])){
        continue_program($_COOKIE["customer_id"]);
    }else{

        include('dbconfig.php');
        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
        // Grabs login information from frontend
        @$username = mysqli_real_escape_string($conn, $_POST["username"]);
        @$password = mysqli_real_escape_string($conn, $_POST["password"]);

        // If empty, does not allow program to proceed
        if($username == "" || $password == ""){
            mysqli_close($conn);
            die("<div class='error-message'>Cannot login with empty username or password</div>");
        }

        // Checks if username password combo is correct
        $sql = "SELECT * FROM 2020F_nadeems.CUSTOMER WHERE login_id='$username'";
        $result = mysqli_query($conn, $sql);

        if(!(mysqli_num_rows($result) == 0)){
            $row = mysqli_fetch_assoc($result);
            if($row["password"] == $password){
                 //If password matches
                $cust_id = $row["customer_id"];
                setcookie("customer_id", $cust_id, time() + (86400*30), "/");
               
                mysqli_close($conn);
                continue_program($cust_id);
            }else{
                // If password doesn't match
                mysqli_close($conn);
                die("<div class='error-message'>Customer $username exist in the database, but the password does not match. Please<a href='customer_login.php'>try again.</a></div>");
            }
        }else{
            // If user does not exist in db
            mysqli_close($conn);
            die("<div class='error-message'>Login ID $username does not exist. Please <a href='customer_login.php'>try again</a></div>");
        }
}

function continue_program($id){

    include('dbconfig.php');
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
    $sql = "SELECT * FROM 2020F_nadeems.CUSTOMER WHERE customer_id=$id";
    $result = mysqli_query($conn, $sql);

    if($result){
        $row = mysqli_fetch_assoc($result);
        echo "<div class='user-info'>";
        echo "<div class='user-info-item'> Welcome customer: <strong>".$row["first_name"]." ".$row["last_name"]."</strong></div>";
        echo "<div class='user-info-item'>". $row["address"].", ".$row["city"].", ".$row["state"]." ".$row["zipcode"]."</div>";
        // Displays User's IP
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "<div class='user-info-item'>Your IP: $ip</div>";

        // Checks if from kean
        $ip_breakdown = explode(".", $ip);
        if(($ip_breakdown[0] == 131 && $ip_breakdown[1] == 125) || $ip_breakdown[0] == 10){
            echo "<div class='user-info-item'>You are from Kean University</div>";
        }else{
            echo "<div class='user-info-item'>You are NOT from Kean University</div>";
        }

        echo "<div class='user-logout'><a href='customer_logout.php'>Customer Logout</a></div>";
        echo "<div class='user-link'><a href='customer_update.php'>Update my data</a></div>";
        echo "<div class='user-link' style='margin-top: 15px;'><a href='index.php'>Project home</a></div><br>";
        
    
    echo "</div>";
    }
}
?>
</body>
</html>