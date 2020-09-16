<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Your Data</title>
    <?php include 'libraries.php' ?>
</head>
<body>
    
<?php 

    if(!isset($_COOKIE["customer_id"])){
        die("<div class='error-message'>You must be logged to update your data. Login <a href='customer_login.php'>here</a></div>");
    }

    include('dbconfig.php');

    $id = $_COOKIE["customer_id"];
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $sql = "SELECT * FROM 2020F_nadeems.CUSTOMER WHERE customer_id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $name = $row['first_name'] . " " . $row['last_name'];
    echo "
    <div class='header'>
        <div class='header-text'> $name's Information</div>
    </div>
    <div class='center-link'><a href='customer_logout.php'>Customer logout</a></div>
    <br>
    <table style='width: 65%;'>
        <tr style='background: gray;'>
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
        </tr>";

    // States array
    // From https://gist.github.com/maxrice/2776900#gistcomment-869296
    $states_array = array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming'
    );

    // Form for updating
    echo "<form action='customer_update_action.php' method='POST'>";
    echo '<tr>';
    echo '<td class="center-table-item">' . $row['customer_id'] . '</td>';
    echo '<td>' . $row['login_id'] . '</td>';
    echo '<td><input type="password" value="' . $row['password'] . '" name="password"></td>';
    echo '<td><input type="text" value="' . $row['last_name'] . '" name="last_name"></td>';
    echo '<td><input type="text" value="' . $row['first_name'] . '" name="first_name"></td>';
    echo '<td><input type="text" value="' . $row['TEL'] . '" name="tel"></td>';
    echo '<td><input type="text" value="' . $row['address'] . '" name="address"></td>';
    echo '<td><input type="text" value="' . $row['city'] . '" name="city"></td>';
    echo '<td><input type="text" value="' . $row['zipcode'] . '" name="zipcode"></td>';
    echo '<td><select name="state">';

    //State selection logic
    $state = $row['state'];
    foreach($states_array as $abri => $fullName){
        if($abri == $state){
            echo "<option value='$abri' selected>$fullName</option>";
        }else{
            echo "<option value='$abri'>$fullName</option>";
        }
    }

    echo "</select>";
    echo '</tr>';
    echo '</table>';
    echo "<div class='submit'><input type='submit' value='Update Information'></div>";
    echo "</form>";

    echo "<div class='center-link' style='margin-top: 10px;'><a href='customer_home.php'>Customer Home</a></div>";
    echo "<div class='center-link'><a href='phase1.php'>Project Home</a></div>";

    mysqli_close($conn);
?>

</body>
</html>