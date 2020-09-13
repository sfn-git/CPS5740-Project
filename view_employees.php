<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Table</title>
    <?php include 'libraries.php'?>
</head>
</html>
<body>
<?php 

    include 'dbconfig.php';

    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $sql = "SELECT * FROM CPS5740.EMPLOYEE";
    $result = mysqli_query($conn, $sql);
    
    if($result){
        echo '<div class="header">
                <div class="header-text">Employees Table</div>
              </div>
              <br>';
        echo '<table>';
        echo '<tr class="table_header">';
        echo '<th>Employee ID</th>';
        echo '<th>Username</th>';
        echo '<th>Password</th>';
        echo '<th>Name</th>';
        echo '<th>Role</th>';
        echo '</tr>';
        while($row = mysqli_fetch_assoc($result)){
            echo '<tr>';
            echo '<td class="center-table-item">' . $row['employee_id'] . '</td>';
            echo '<td>' . $row['login'] . '</td>';
            echo '<td>' . $row['password'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td class="center-table-item">' . $row['role'] . '</td>';
            echo '</tr>';
            
        }
        echo '</table>';
    }

    mysqli_close($conn);

?>
</body>