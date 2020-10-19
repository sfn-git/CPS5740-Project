<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Home</title>
    <?php include 'libraries.php'?>
</head>
<body>
<?php

    if(isset($_COOKIE["employee_id"])){
        continue_program($_COOKIE["employee_id"]);
    }else{

        include('dbconfig.php');
        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
        // Grabs login information from frontend
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        // If empty, does not allow program to proceed
        if($username == "" || $password == ""){
            mysqli_close($conn);
            die("<div class='error-message'>Cannot login with empty username or password</div>");
        }

        // Checks if username password combo is correct
        $sql = "SELECT employee_id, login, password, role FROM CPS5740.EMPLOYEE2 WHERE login='$username'";
        $result = mysqli_query($conn, $sql);

        if(!(mysqli_num_rows($result) == 0)){
            $row = mysqli_fetch_assoc($result);
            if($row["password"] == hash("sha256", $password)){
                $em_id = $row["employee_id"];
                setcookie("employee_id", $em_id, time() + (86400*30), "/");
                if($row['role'] == 'M'){
                    setcookie("is_manager", 1, time() + (86400*30), "/");
                }else{
                    setcookie("is_manager", 0, time() + (86400*30), "/");
                }
               
                mysqli_close($conn);
                continue_program($em_id);
            }else{
                mysqli_close($conn);
                die("<div class='error-message'>Employee $username exist in the database, but the password does not match. Please<a href='employee_login.php'>try again.</a></div>");
            }
        }else{
            mysqli_close($conn);
            die("<div class='error-message'>Login ID $username does not exist. Please <a href='employee_login.php'>try again</a></div>");
        }
}

function continue_program($id){
    
    $user;

    echo "<div class='user-info'>";

    include('dbconfig.php');
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
    
    $sql = "SELECT * FROM CPS5740.EMPLOYEE2 WHERE employee_id=$id";
    $result = mysqli_query($conn, $sql);

    if($result){
        $user = mysqli_fetch_assoc($result);
    }
    mysqli_close($conn);

    if($user["role"] == "M"){
        echo "<div class='user-info-item'>Welcome Manager: ". $user['name'] . "</div>";
        echo "<div class='user-logout margin-top'><a href='employee_logout.php'>Manager Logout</a></div>";
    }else if($user["role"] == "E"){
        echo "<div class='user-info-item'>Welcome Employee: " . $user['name'] . "</div>";
        echo "<div class='user-logout margin-top'><a href='employee_logout.php'>Employee Logout</a></div>";
    }

    echo "<div class='user-link margin-top'><a href='employee_add_product.php'>Add Product</a></div>";
    echo "<div class='user-link'><a href='employee_search_update.php'>Search and Update Product</a></div>";
    echo "<div class='user-link margin-bottom'><a href='employee_view_vendors.php'>View Vendors</a></div>";
    echo "<div class='user-link'><a href='phase2.php'>Project Home</a></div>";
    echo "</div>";
}
?>
</body>
</html>