<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vendors</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='phase2.php'>Project Home</a>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='employee_home.php'>Employee Home</a>
    </div>
    <div class="header">
        <div class="header-text">View Vendors</div>
    </div>
    <?php
        if(!isset($_COOKIE["employee_id"])){
            die("<div class='error-message'>You must be logged in as an employee or manager</div>");
        }
        include('dbconfig.php');

        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

        $sql = "SELECT vendor_id, name, address, city, state, zipcode, CONCAT(latitude, \",\", Longitude) as location FROM CPS5740.VENDOR";
        $result = mysqli_query($conn, $sql);

        $locations = array();
        
        // Whole lot of javascript
        echo "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>";
        echo "<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>";
        echo "<script>";
        echo "google.charts.load('current', {'packages':['table']});";
        echo "google.charts.setOnLoadCallback(drawTable);";
        echo "function drawTable(searchResult) {";
        echo "var data = new google.visualization.DataTable();";
        echo "data.addColumn('number', 'Vendor ID');";
        echo "data.addColumn('string', 'Vendor Name');";
        echo "data.addColumn('string', 'Address');";
        echo "data.addColumn('string', 'City');";
        echo "data.addColumn('string', 'State');";
        echo "data.addColumn('string', 'Zipcode');";
        echo "data.addColumn('string', 'Location (Lat, Lon)');";
        echo "var table = new google.visualization.Table(document.getElementById('table_div'));";
        
        
        while($row = mysqli_fetch_assoc($result)){
            
            echo "data.addRow([";
            echo $row['vendor_id'].", '".$row['name']."', '".$row['address']."','".$row['city']."','".$row['state']."','".$row['zipcode']."','(".$row['location'];
            echo ")']);";
            array_push($locations, $row["location"]);
        }
        echo "table.draw(data, {showRowNumber: false, width: '100%', height: '100%'});";
        echo "}";
        echo "</script>";

        

        mysqli_close($conn);
    ?>
    <div id="table_div"></div>
</body>
</html>