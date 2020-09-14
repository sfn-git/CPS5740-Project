<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Home</title>
</head>
<body>
<?php

    // Grabs login information from frontend
    @$username = $_POST["username"];
    @$password = $_POST["password"];

    // If empty, does not allow program to proceed
    if($username == "" || $password == ""){
        die("<h1>Cannot login with empty username or password</h1>");
    }

    include 'dbconfig.php';
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    // Checks if username password combo is correct
    $sql = "SELECT employee_id, login, password FROM CPS5740.EMPLOYEE WHERE login='$username'";
    $result = mysqli_query($conn, $sql);

    if(!(mysqli_num_rows($result) == 0)){
        $row = mysqli_fetch_assoc($result);
        if($row["password"] == $password){
            $em_id = $row["employee_id"];
            setcookie("employee_id", $em_id, time() + (86400*30), "/");
            mysqli_close($conn);
            continue_program($em_id);
        }else{
            mysqli_close($conn);
            die("Employee $username exist in the database, but the password does not match. Please <a href='employee_login.php'>try again</a>");
        }
    }else{
        mysqli_close($conn);
        die("Login ID $username does not exist . Please <a href='employee_login.php'>try again</a>");
    }

function continue_program($id){
    
    $user;

    // Displays User's IP
    $ip = $_SERVER['REMOTE_ADDR'];
    echo "Your IP: $ip";
    echo "<br>";

    // Checks if from kean
    $ip_breakdown = explode(".", $ip);
    if(($ip_breakdown[0] == 131 && $ip_breakdown[1] == 125) || $ip_breakdown[0] == 10){
        echo "You are from Kean University";
    }else{
        echo "You are NOT from Kean University";
    }
    echo "<br>";

    include 'dbconfig.php';
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
    
    $sql = "SELECT * FROM CPS5740.EMPLOYEE WHERE employee_id=$id";
    $result = mysqli_query($conn, $sql);

    if($result){
        $user = mysqli_fetch_assoc($result);
    }
    mysqli_close($conn);

    if($user["role"] == "M"){
        echo "Welcome manager: ". $user['name'];
        echo "<br>";
        echo "<a href='employee_logout.php'>Manager Logout</a>";
    }else if($user["role"] == "E"){
        echo "Welcome employee: " . $user['name'];
        echo "<br>";
        echo "<a href='employee_logout.php'>Employee Logout</a>";
    }
}
?>
</body>
</html>