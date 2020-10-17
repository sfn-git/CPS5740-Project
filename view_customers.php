<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <?php include 'libraries.php';?>
</head>
<body>
<?php 

    if(!isset($_COOKIE["employee_id"])){
        die("<div class='error-message'>You must be logged in as an employee or manager</div>");
    }

    include('dbconfig.php');

    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $sql = "SELECT * FROM 2020F_nadeems.CUSTOMER";
    $result = mysqli_query($conn, $sql);

    echo '
    <div class="header">
        <div class="header-text">Customer Table</div>
    </div>
    <br>
    <table style="width: 65%;">
        <tr style="background: gray;">
            <th>ID</th>
            <th>Login</th>
            <th>Password</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>TEL</th>
            <th>Address</th>
            <th>City</th>
            <th>Zipcode</th>
            <th>State</th>
        </tr>
    ';

    while($row = mysqli_fetch_assoc($result)){
        echo '<tr>';
        echo '<td><div class="center-table-item">' . $row['customer_id'] . '</div></td>';
        echo '<td>' . $row['login_id'] . '</td>';
        echo '<td>' . $row['password'] . '</td>';
        echo '<td>' . $row['last_name'] . '</td>';
        echo '<td>' . $row['first_name'] . '</td>';
        echo '<td>' . $row['TEL'] . '</td>';
        echo '<td>' . $row['address'] . '</td>';
        echo '<td>' . $row['city'] . '</td>';
        echo '<td>' . $row['zipcode'] . '</td>';
        echo '<td>' . $row['state'] . '</td>';
        echo '</tr>';
        
    }

        echo '</table>';

    mysqli_close($conn);

?>

</body>
</html>